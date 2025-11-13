<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index()
    {
        return Pago::with('pedido')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'metodo' => 'required|string',
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
        ]);

        $pago = Pago::create($data);
        return response()->json($pago, 201);
    }

    public function show(Pago $pago)
    {
        return $pago->load('pedido');
    }

    public function update(Request $request, Pago $pago)
    {
        $data = $request->validate([
            'metodo' => 'sometimes|string',
            'monto' => 'sometimes|numeric|min:0',
            'fecha_pago' => 'sometimes|date',
        ]);

        $pago->update($data);
        return response()->json($pago);
    }

    public function destroy(Pago $pago)
    {
        $pago->delete();
        return response()->json(['message' => 'Pago eliminado']);
    }
}
