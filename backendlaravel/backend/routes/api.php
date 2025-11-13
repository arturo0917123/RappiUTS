<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductoController;
use App\Http\Controllers\API\PedidoController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\PagoController;
use App\Http\Controllers\API\FavorController;
use App\Http\Controllers\API\EmprendimientoController;
use App\Http\Controllers\API\ResenaController;
use App\Http\Controllers\API\NotificacionController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rutas principales del backend PedidosUTS
| Protegidas por Sanctum y con endpoints para login, productos y pedidos.
|
*/

// 🧭 Ruta base
Route::get('/', function () {
    return response()->json([
        'message' => 'Bienvenido a la API de Pedidos UTS 🚀',
        'version' => '1.0.0',
        'author' => 'kirimonda'
    ]);
});



// 🧑‍💻 Autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');




    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    // Roles
    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::get('/roles/{role}', [RoleController::class, 'show']);
    Route::put('/roles/{role}', [RoleController::class, 'update']);
    Route::delete('/roles/{role}', [RoleController::class, 'destroy']);


// Productos
Route::get('/productos', [ProductoController::class, 'index']);
Route::post('/productos', [ProductoController::class, 'store']); // Crear
Route::get('/productos/{producto}', [ProductoController::class, 'show']);
Route::put('/productos/{producto}', [ProductoController::class, 'update']); // Editar
Route::delete('/productos/{producto}', [ProductoController::class, 'destroy']); // Eliminar

// Pedidos
Route::get('/pedidos', [PedidoController::class, 'index']);
Route::post('/pedidos', [PedidoController::class, 'store']); // Crear
Route::get('/pedidos/{pedido}', [PedidoController::class, 'show']);
Route::put('/pedidos/{pedido}', [PedidoController::class, 'update']); // Editar
Route::delete('/pedidos/{pedido}', [PedidoController::class, 'destroy']); // Eliminar

// Pagos
Route::get('/pagos', [PagoController::class, 'index']);
Route::post('/pagos', [PagoController::class, 'store']); // Crear
Route::get('/pagos/{pago}', [PagoController::class, 'show']);
Route::put('/pagos/{pago}', [PagoController::class, 'update']); // Editar
Route::delete('/pagos/{pago}', [PagoController::class, 'destroy']); // Eliminar


// Favores
Route::get('/favores', [FavorController::class, 'index']);
Route::post('/favores', [FavorController::class, 'store']); // Crear
Route::get('/favores/{favor}', [FavorController::class, 'show']);
Route::put('/favores/{favor}', [FavorController::class, 'update']); // Editar
Route::delete('/favores/{favor}', [FavorController::class, 'destroy']); // Eliminar


// Emprendimientos
Route::get('/emprendimientos', [EmprendimientoController::class, 'index']);
Route::post('/emprendimientos', [EmprendimientoController::class, 'store']); // Crear
Route::get('/emprendimientos/{emprendimiento}', [EmprendimientoController::class, 'show']);
Route::put('/emprendimientos/{emprendimiento}', [EmprendimientoController::class, 'update']); // Editar
Route::delete('/emprendimientos/{emprendimiento}', [EmprendimientoController::class, 'destroy']); // Eliminar

// Reseñas
Route::get('/resenas', [ResenaController::class, 'index']);
Route::post('/resenas', [ResenaController::class, 'store']); // Crear
Route::get('/resenas/{resena}', [ResenaController::class, 'show']);
Route::put('/resenas/{resena}', [ResenaController::class, 'update']); // Editar
Route::delete('/resenas/{resena}', [ResenaController::class, 'destroy']); // Eliminar


// Notificaciones
Route::get('/notificaciones', [NotificacionController::class, 'index']);
Route::post('/notificaciones', [NotificacionController::class, 'store']); // Crear
Route::get('/notificaciones/{notificacion}', [NotificacionController::class, 'show']);
Route::put('/notificaciones/{notificacion}', [NotificacionController::class, 'update']); // Editar
Route::delete('/notificaciones/{notificacion}', [NotificacionController::class, 'destroy']); // Eliminar
