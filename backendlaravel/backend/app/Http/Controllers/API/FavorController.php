<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Favor;
use Illuminate\Http\Request;

class FavorController extends Controller
{
    public function index()
    {
        return Favor::with('user')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'descripcion' => 'required|string',
            'recompensa' => 'nullable|numeric|min:0',
            'estado' => 'sometimes|string|max:50',
        ]);

        $favor = Favor::create($data);

        return response()->json($favor, 201);
    }

    public function show(Favor $favor)
    {
        return $favor->load('user');
    }

    public function update(Request $request, Favor $favor)
    {
        $data = $request->validate([
            'descripcion' => 'sometimes|string',
            'recompensa' => 'sometimes|numeric|min:0',
            'estado' => 'sometimes|string|max:50',
        ]);

        $favor->update($data);

        return response()->json($favor);
    }

    public function destroy(Favor $favor)
    {
        $favor->delete();

        return response()->json(['message' => 'Favor eliminado']);
    }
}
