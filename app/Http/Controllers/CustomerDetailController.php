<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CustomerDetail;
use Illuminate\Http\Request;

class CustomerDetailController extends Controller
{
    public function index()
    {
        $customerDetails = CustomerDetail::with('company')->paginate(10);
        return view('customer-details.index', compact('customerDetails'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('customer-details.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'identification' => 'required|unique:customer_details',
            'full_name' => 'required',
            'email' => 'required|email|unique:customer_details',
            'phone' => 'required',
            'address' => 'required',
            'company_id' => 'required|exists:companies,id',
        ]);

        CustomerDetail::create($validatedData);

        $this->flashNotification('success', 'Cliente Creado', 'El cliente ha sido creado exitosamente.');
        return redirect()->route('customer-details.index');
    }

    public function show($id)
    {
        $customer = CustomerDetail::with(['company'])->findOrFail($id);
        return view('customer-details.show', compact('customer'));
    }

    public function edit($id)
    {
        $companies = Company::all();
        $customerDetail = CustomerDetail::findOrFail($id);
        return view('customer-details.edit', compact('customerDetail', 'companies'));
    }

    public function update(Request $request, $id)
    {
        $customerDetail = CustomerDetail::findOrFail($id);

        $validated = $request->validate([
            'identification' => 'required|unique:customer_details,identification,' . $customerDetail->id,
            'full_name' => 'required',
            'email' => 'required|email|unique:customer_details,email,' . $customerDetail->id,
            'phone' => 'required',
            'address' => 'required',
            'company_id' => 'required|exists:companies,id',
        ]);

        $customerDetail->update($validated);

        $this->flashNotification('success', 'Cliente Actualizado', 'El cliente ha sido actualizado exitosamente.');
        return redirect()->route('customer-details.index');
    }

    public function destroy(CustomerDetail $customerDetail)
    {
        $customerDetail->delete();

        $this->flashNotification('success', 'Cliente Eliminado', 'El cliente ha sido eliminado exitosamente.');
        return redirect()->route('customer-details.index');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $customers = CustomerDetail::where('full_name', 'like', "%{$query}%")
            ->orWhere('identification', 'like', "%{$query}%")
            ->get();

        return response()->json($customers);
    }

    public function createCustomers($customers){
        try {
            foreach ($customers as $customer) {
                $existingCustomer = CustomerDetail::where('identification', $customer['identification'])->first();
        
                if ($existingCustomer) {
                    // Actualiza el producto existente
                    $existingCustomer->update([
                        'identification' => $customer['identification'],
                        'full_name' => $customer['full_name'],
                        'email' => $customer['email'],
                        'phone' => $customer['phone'],
                        'address' => $customer['address'],
                        'company_id' => $customer['company_id'],
                        'updated_at' => now(),
                    ]);
                } else {
                    CustomerDetail::create([
                        'identification' => $customer['identification'],
                        'full_name' => $customer['full_name'],
                        'email' => $customer['email'],
                        'phone' => $customer['phone'],
                        'address' => $customer['address'],
                        'company_id' => $customer['company_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }catch (\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeCustomers(Request $request){
        try {
            $request->validate([
                'customers' => 'required|array',
                'customers.*.identification' => 'required|numeric|digits:20',
                'customers.*.full_name' => 'required|string|max:255',
                'customers.*.email' => 'email',
                'customers.*.phone' => 'string',
                'customers.*.address' => 'string',
                'customers.*.company_id' => 'required|integer|exists:companies,id',
            ]);

            $customers = $request->input('customers');
            $this->createCustomers($customers);

            return response()->json(['status' => true, 'message' => 'Clientes creados o actualizados exitosamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
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
