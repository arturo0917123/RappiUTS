<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Resena;
use Illuminate\Http\Request;

class ResenaController extends Controller
{
    public function index()
    {
        return Resena::with('user')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string',
            'fecha' => 'required|date',
        ]);

        $resena = Resena::create($data);
        return response()->json($resena, 201);
    }

    public function show(Resena $resena)
    {
        return $resena->load('user');
    }

    public function update(Request $request, Resena $resena)
    {
        $data = $request->validate([
            'calificacion' => 'sometimes|integer|min:1|max:5',
            'comentario' => 'nullable|string',
            'fecha' => 'sometimes|date',
        ]);

        $resena->update($data);
        return response()->json($resena);
    }

    public function destroy(Resena $resena)
    {
        $resena->delete();
        return response()->json(['message' => 'Reseña eliminada']);
    }
}
