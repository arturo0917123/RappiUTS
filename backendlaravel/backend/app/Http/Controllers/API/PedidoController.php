<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        return Pedido::with(['user', 'detalles', 'pago'])->get();
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

        return response()->json([
            "message" => "Pedido creado correctamente",
            "pedido"  => $pedido
        ], 201);
    }

    public function show($id)
    {
        $pedido = Pedido::with(['user', 'detalles', 'pago'])->findOrFail($id);
        return $pedido;
    }

    public function update(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        $data = $request->validate([
            'fecha' => 'sometimes|date',
            'estado' => 'sometimes|string',
            'total' => 'sometimes|numeric|min:0',
        ]);

        $pedido->update($data);

        return response()->json([
            "message" => "Pedido actualizado",
            "pedido"  => $pedido
        ]);
    }

    public function destroy($id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->delete();

        return response()->json([
            "message" => "Pedido eliminado correctamente"
        ]);
    }
}
