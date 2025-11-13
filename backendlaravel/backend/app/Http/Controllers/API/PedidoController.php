<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        return Pedido::with(['user', 'detalles.producto', 'pago'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'estado' => 'required|string',
            'total' => 'required|numeric|min:0',
        ]);

        $pedido = Pedido::create($data);
        return response()->json($pedido, 201);
    }

    public function show(Pedido $pedido)
    {
        return $pedido->load(['user', 'detalles.producto', 'pago']);
    }

    public function update(Request $request, Pedido $pedido)
    {
        $data = $request->validate([
            'estado' => 'sometimes|string',
            'total' => 'sometimes|numeric|min:0',
        ]);

        $pedido->update($data);
        return response()->json($pedido);
    }

    public function destroy(Pedido $pedido)
    {
        $pedido->delete();
        return response()->json(['message' => 'Pedido eliminado']);
    }
}
