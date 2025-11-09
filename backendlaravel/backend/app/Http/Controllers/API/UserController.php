<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Lista de usuarios
    public function index()
    {
        return response()->json(
            User::with('role')->get()
        );
    }

    // Crear usuario
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|string',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json($user, 201);
    }

    // Ver usuario
    public function show(User $user)
    {
        return response()->json($user->load('role'));
    }

    // Actualizar usuario
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => "sometimes|email|unique:users,email,{$user->id}",
            'password' => 'sometimes|min:8',
            'role_id' => 'sometimes|exists:roles,id',
            'phone' => 'nullable|string',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|string',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json($user);
    }

    // Eliminar usuario
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado']);
    }
}
