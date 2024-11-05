<?php

namespace App\Http\Controllers;

use App\Models\CustomerDetail;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Los administradores ven todas las órdenes, los distribuidores solo las suyas
        $orders = $user->role === 'admin'
            ? Order::latest()->get()// Ordenar por fecha de creación descendente y paginar
            : Order::where('user_id', $user->id)->latest()->paginate(15);

        return view('orders.index', compact('orders'));
    }
    // Método para mostrar una orden específica
    public function show(Order $order)
    {
        $user = Auth::user();

        // Verifica si el usuario es un distribuidor que intenta acceder a su propia orden
        if ($user->role->name === 'distributor' && $user->id !== $order->user_id) {
            abort(403, 'No tienes permiso para ver esta orden.');
        }

        return view('orders.show', compact('order'));
    }

    public function create()
    {
        $products = Product::all();
        $customers = CustomerDetail::all();

        return view('orders.create', compact('products', 'customers'));
    }


    public function store(Request $request)
    {
        // Validar los datos enviados desde el formulario
        $request->validate([
            'customer_id' => 'required|exists:customer_details,id',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'numeric|min:1',
            'observations' => 'nullable|string|max:255',
        ]);

        // Crear una nueva orden
        $order = new Order();
        $order->user_id = Auth::id(); // Usuario autenticado (distribuidor)
        $order->customer_id = $request->customer_id;
        $order->status = 'pendiente';
        $order->subtotal = 0; // Se calculará después
        $order->total_tax = 0; // Se calculará después
        $order->total = 0; // Se calculará después
        $order->observations = $request->observations;
        $order->save();

        // Variables para acumular el subtotal, impuestos y total
        $subtotal = 0;
        $totalTax = 0;

        // Procesar cada producto agregado a la orden
        foreach ($request->products as $index => $productId) {
            $product = Product::findOrFail($productId);
            $quantity = $request->quantities[$index];
            $priceWithTax = $product->getPriceWithTax();

            $lineSubtotal = $product->base_price * $quantity;
            $lineTotalTax = ($priceWithTax - $product->base_price) * $quantity;
            $lineTotal = $priceWithTax * $quantity;

            // Agregar los productos a la orden (en la tabla pivote)
            $order->products()->attach($product->id, [
                'quantity' => $quantity,
                'subtotal' => $lineSubtotal,
                'total_tax' => $lineTotalTax,
                'total' => $lineTotal,
            ]);

            // Actualizar el subtotal y los impuestos
            $subtotal += $lineSubtotal;
            $totalTax += $lineTotalTax;
        }

        // Actualizar los totales de la orden
        $order->subtotal = $subtotal;
        $order->total_tax = $totalTax;
        $order->total = $subtotal + $totalTax;
        $order->save();

        $this->flashNotification('success', 'Orden Creada', 'La orden ha sido creada exitosamente.');
        return redirect()->route('orders.index');
    }

    public function edit(Order $order)
    {
        $order = Order::with('products')->findOrFail($order->id); // Carga el pedido junto con los productos relacionados
        $products = Product::all();
        $customers = CustomerDetail::all();

        return view('orders.edit', compact('order', 'products', 'customers'));
    }

    public function update(Request $request, Order $order)
    {
        $user = Auth::user();

        // Verificar si el usuario autenticado tiene permiso para editar la orden
        if ($user->role->name === 'distributor' && $user->id !== $order->user_id) {
            // Si el usuario es un distribuidor y no creó la orden, no tiene permiso
            abort(403, 'No tienes permiso para actualizar esta orden.');
        }

        // Validar los datos enviados desde el formulario
        $request->validate([
            'customer_id' => 'required|exists:customer_details,id',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'numeric|min:1',
            'observations' => 'nullable|string|max:255',
        ]);

        // Actualizar la información básica de la orden
        $order->customer_id = $request->customer_id;
        $order->status = $request->status;
        $order->subtotal = 0;
        $order->total_tax = 0;
        $order->total = 0;
        $order->observations = $request->observations;
        $order->save();

        // Eliminar los productos anteriores de la orden
        $order->products()->detach();

        // Variables para acumular el subtotal, impuestos y total
        $subtotal = 0;
        $totalTax = 0;

        // Procesar cada producto agregado a la orden
        foreach ($request->products as $index => $productId) {
            $product = Product::findOrFail($productId);
            $quantity = $request->quantities[$index];
            $priceWithTax = $product->getPriceWithTax();

            $lineSubtotal = $product->base_price * $quantity;
            $lineTotalTax = ($priceWithTax - $product->base_price) * $quantity;
            $lineTotal = $priceWithTax * $quantity;

            // Agregar los productos a la orden (en la tabla pivote)
            $order->products()->attach($product->id, [
                'quantity' => $quantity,
                'subtotal' => $lineSubtotal,
                'total_tax' => $lineTotalTax,
                'total' => $lineTotal,
            ]);

            // Actualizar el subtotal y los impuestos
            $subtotal += $lineSubtotal;
            $totalTax += $lineTotalTax;
        }

        // Actualizar los totales de la orden
        $order->subtotal = $subtotal;
        $order->total_tax = $totalTax;
        $order->total = $subtotal + $totalTax;
        $order->save();

        $this->flashNotification('success', 'Orden Actualizada', 'La orden ha sido actualizada exitosamente.');
        return redirect()->route('orders.index');
    }


    public function destroy($id)
    {
        // Buscar la orden por su ID
        $order = Order::findOrFail($id);

        // Eliminar todos los productos asociados a la orden
        $order->products()->detach();

        // Ahora eliminar la orden
        $order->delete();

        $this->flashNotification('success', 'Orden Eliminada', 'La orden ha sido eliminada exitosamente.');
        return redirect()->route('orders.index');
    }

    /* FUNCIONES APARTE DEL CRUD */
    /* Descargar órdenes del sistema mediante url*/
    public function downloadOrders($companyId, $status)
    {
        // Validar que el estado sea 0 o 1
        if (!in_array($status, [0, 1])) {
            return response()->json(['error' => 'Estado inválido'], 400);
        }

        // Convertir el parámetro de estado en el texto correspondiente
        $statusText = $status == 0 ? 'pendiente' : 'facturado';

        // Obtener las órdenes según los parámetros
        $orders = Order::whereHas('user', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
            ->where('status', $statusText)
            ->with(['customer', 'products', 'user']) 
            ->get();

        // Formato JSON de la respuesta
        $formattedOrders = $orders->map(function ($order) {
            return [
                'document' => $order->id,
                'customer' => [
                    'identification' => $order->customer->identification, 
                    'name' => $order->customer->full_name, 
                ],
                'user' => $order->user->name,
                'status' => $order->status,
                "total" => $order->total,
                'products' => $order->products->map(function ($product) {
                    return [
                        'name' => $product->name,
                        'code' => $product->code,
                        'base_price' => $product->base_price,
                        'tax_rate' => $product->tax_rate,
                        'company_id' => $product->company_id,
                        'quantity' => $product->pivot->quantity,
                    ];
                }),
            ];
        });

        // Devolver las órdenes como un arreglo JSON
        return response()->json($formattedOrders);
    }

    public function updateOrderStatus($orderId)
    {
        // Buscar la orden por su ID
        $order = Order::findOrFail($orderId);

        // Actualizar el estado de la orden
        $order->update(['status' => 'facturado']);	
        return response()->json(['message' => 'Estado de la orden actualizado']);
    }


    private function flashNotification($type, $title, $message)
    {
        session()->flash('notification', [
            'type' => $type,
            'title' => $title,
            'message' => $message
        ]);
    }
}
