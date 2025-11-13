<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index()
    {
        return Notificacion::with('user')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'mensaje' => 'required|string|max:255',
            'fecha_envio' => 'required|date',
            'leida' => 'boolean',
        ]);

        $notificacion = Notificacion::create($data);
        return response()->json($notificacion, 201);
    }

    public function show(Notificacion $notificacion)
    {
        return $notificacion->load('user');
    }

    public function update(Request $request, Notificacion $notificacion)
    {
        $data = $request->validate([
            'mensaje' => 'sometimes|string|max:255',
            'fecha_envio' => 'sometimes|date',
            'leida' => 'sometimes|boolean',
        ]);

        $notificacion->update($data);
        return response()->json($notificacion);
    }

    public function destroy(Notificacion $notificacion)
    {
        $notificacion->delete();
        return response()->json(['message' => 'Notificación eliminada']);
    }
}
