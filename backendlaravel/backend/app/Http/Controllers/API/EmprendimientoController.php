<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Emprendimiento;
use Illuminate\Http\Request;

class EmprendimientoController extends Controller
{
    public function index()
    {
        return Emprendimiento::with(['user', 'productos'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:50',
        ]);

        $emprendimiento = Emprendimiento::create($data);
        return response()->json($emprendimiento, 201);
    }

    public function show(Emprendimiento $emprendimiento)
    {
        return $emprendimiento->load(['user', 'productos']);
    }

    public function update(Request $request, Emprendimiento $emprendimiento)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:50',
        ]);

        $emprendimiento->update($data);
        return response()->json($emprendimiento);
    }

    public function destroy(Emprendimiento $emprendimiento)
    {
        $emprendimiento->delete();
        return response()->json(['message' => 'Emprendimiento eliminado']);
    }

    public function getByUser($user_id)
    {
        $emprendimientos = Emprendimiento::with(['user', 'productos'])
            ->where('user_id', $user_id)
            ->get();
        return response()->json($emprendimientos);
    }
}
