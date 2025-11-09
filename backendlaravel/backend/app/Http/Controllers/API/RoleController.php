<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Lista de roles
    public function index()
    {
        return Role::all();
    }

    // Crear rol
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'display_name' => 'nullable|string',
        ]);

        $role = Role::create($data);

        return response()->json($role, 201);
    }

    // Mostrar rol
    public function show(Role $role)
    {
        return $role;
    }

    // Actualizar rol
    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => "required|string|unique:roles,name,{$role->id}",
            'display_name' => 'nullable|string',
        ]);

        $role->update($data);

        return response()->json($role);
    }

    // Eliminar rol
    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(['message' => 'Rol eliminado']);
    }
}
