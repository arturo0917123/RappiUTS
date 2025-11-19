<?php

namespace App\Http\Controllers\API;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController
{
    // Mostrar todas las notificaciones
    public function index()
    {
        return response()->json(
            Notificacion::orderBy('id', 'desc')->get()
        );
    }

    // Crear una notificación
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'         => 'required|integer',
            'destinatario_id' => 'required|integer',
            'mensaje'         => 'required|string|max:255',
            'fecha_envio' => 'required|date_format:Y-m-d H:i:s',
            'leida'           => 'nullable|boolean',
            'estado'          => 'nullable|string|max:25',
            'respuesta'       => 'nullable|string|max:255'
        ]);

        $notificacion = Notificacion::create($validated);

        return response()->json($notificacion, 201);
    }

    // Mostrar una notificación por ID
    public function show($id)
    {
        $notificacion = Notificacion::find($id);

        if (!$notificacion) {
            return response()->json(['error' => 'Notificación no encontrada'], 404);
        }

        return response()->json($notificacion);
    }

    // Actualizar una notificación
    public function update(Request $request, $id)
    {
        $notificacion = Notificacion::find($id);

        if (!$notificacion) {
            return response()->json(['error' => 'Notificación no encontrada'], 404);
        }

        $validated = $request->validate([
            'mensaje'     => 'nullable|string|max:255',
            'leida'       => 'nullable|boolean',
            'estado'      => 'nullable|string|max:25',
            'respuesta'   => 'nullable|string|max:255'
        ]);

        $notificacion->update($validated);

        return response()->json($notificacion);
    }

    // Eliminar una notificación
    public function destroy($id)
    {
        $notificacion = Notificacion::find($id);

        if (!$notificacion) {
            return response()->json(['error' => 'Notificación no encontrada'], 404);
        }

        $notificacion->delete();

        return response()->json(['message' => 'Notificación eliminada']);
    }

    public function destinatario($id)
{
    return response()->json(
        Notificacion::where('destinatario_id', $id)
            ->orderBy('id', 'desc')
            ->get()
    );
}

}
