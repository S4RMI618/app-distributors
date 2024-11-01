<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['company', 'role'])->get();  // Cargar relaciones de compañía y rol
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $companies = Company::all();  // Obtener todas las compañías para el dropdown
        $roles = Role::all();  // Obtener todos los roles
        return view('users.create', compact('companies', 'roles'));
    }

    public function store(Request $request)
    {
        // Validar los datos
        $validated = $request->validate([
            'name' => 'required|unique:users,name|max:255',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:8|confirmed',
            'company_id' => 'required|exists:companies,id',  // Verificar que la compañía existe
            'role_id' => 'required|exists:roles,id',  // Verificar que el rol existe
        ]);

        // Crear un nuevo usuario
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),  // Hash de la contraseña
            'company_id' => $validated['company_id'],
            'role_id' => $validated['role_id'],
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado con éxito');
    }

    public function show($id)
    {
        $user = User::with(['company', 'role'])->findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $companies = Company::all();  // Para el dropdown de compañías
        $roles = Role::all();  // Para el dropdown de roles
        return view('users.edit', compact('user', 'companies', 'roles'));
    }

    public function update(Request $request, $id)
    {
        // Validar los datos
        $validated = $request->validate([
            'name' => 'required|max:255|unique:users,name,' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|min:8|confirmed',  // Contraseña opcional
            'company_id' => 'required|exists:companies,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Buscar el usuario
        $user = User::findOrFail($id);

        // Preparar los datos para actualizar
        $dataToUpdate = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'company_id' => $validated['company_id'],
            'role_id' => $validated['role_id'],
        ];
        // Actualizar la contraseña solo si se proporciona una nueva

        if (!empty($validated['password'])) {
            $dataToUpdate['password'] = Hash::make($validated['password']);
        }

        // Actualizar los datos
        $user->update($dataToUpdate);


        return redirect()->route('users.index')->with('success', 'Usuario actualizado con éxito');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado con éxito');
    }
}
