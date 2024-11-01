<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function createProducts($products)
    {
        foreach ($products as $product) {
            // Busca el producto por código
            $existingProduct = Product::where('code', $product['code'])->first();
    
            if ($existingProduct) {
                // Actualiza el producto existente
                $existingProduct->update([
                    'name' => $product['name'],
                    'base_price' => $product['base_price'],
                    'tax_rate' => $product['tax_rate'],
                    'company_id' => $product['company_id'],
                    'updated_at' => now(),
                ]);
            } else {
                // Crea un nuevo producto
                Product::create([
                    'name' => $product['name'],
                    'code' => $product['code'],
                    'base_price' => $product['base_price'],
                    'tax_rate' => $product['tax_rate'],
                    'company_id' => $product['company_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function getProducts()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        try {
            // Valida la entrada
            $request->validate([
                'products' => 'required|array',
                'products.*.name' => 'required|string|max:255',
                'products.*.code' => 'required|string',
                'products.*.base_price' => 'required|numeric',
                'products.*.tax_rate' => 'required|numeric',
                'products.*.company_id' => 'required|integer|exists:companies,id',
            ]);

            $products = $request->input('products');
            $this->createProducts($products);

            return response()->json(['status' => true, 'message' => 'Productos creados o actualizados exitosamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->get();

        return response()->json($products);
    }
}
