<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(){
        $companies = Company::all();
        return view('companies.index', compact('companies'));
    }

    public function create(){
        return view('companies.create');
    }

    public function store(Request $request){
        $request->validate([
            'nit' => 'required|unique:companies',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'department' => 'required',
            'municipality' => 'required',
        ]);

        Company::create($request->all());

        $this->flashNotification('success', 'Empresa Creada', 'La empresa ha sido creada exitosamente.');
        return redirect()->route('companies.index');
    }

    public function show($id){
        $company = Company::findOrFail($id);
        return view('companies.show', compact('company'));
    }

    public function edit($id){
        $company = Company::find($id);
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company){
        $validated = $request->validate([
            'nit' => 'required|unique:companies,nit,' . $company->id,
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'department' => 'required',
            'municipality' => 'required',
        ]);

        $company->update($validated);

        $this->flashNotification('success', 'Empresa Actualizada', 'La empresa ha sido actualizada exitosamente.');
        return redirect()->route('companies.index');
    }

    public function destroy(Company $company){
        $company->delete();

        $this->flashNotification('success', 'Empresa Eliminada', 'La empresa ha sido eliminada exitosamente.');
        return redirect()->route('companies.index');
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
