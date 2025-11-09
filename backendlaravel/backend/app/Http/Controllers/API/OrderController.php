<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Listar pedidos (admin ve todos, usuario solo los suyos)
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role->name === 'admin') {
            $orders = Order::with('items.product', 'user')->paginate(10);
        } else {
            $orders = Order::with('items.product')
                ->where('user_id', $user->id)
                ->paginate(10);
        }

        return response()->json($orders);
    }

    // Mostrar un pedido específico
    public function show(Order $order, Request $request)
    {
        $user = $request->user();

        if ($user->role->name !== 'admin' && $order->user_id !== $user->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($order->load('items.product', 'user'));
    }

    // Crear pedido
    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'address' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $user = $request->user();

        DB::beginTransaction();
        try {
            $total = 0;

            $order = Order::create([
                'user_id' => $user->id,
                'total' => 0,
                'status' => 'pedido_realizado',
                'address' => $data['address'] ?? null,
                'note' => $data['note'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);

                if (!$product) {
                    throw new \Exception("Producto no encontrado");
                }

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para {$product->name}");
                }

                $product->decrement('stock', $item['quantity']);

                $lineTotal = $product->price * $item['quantity'];
                $total += $lineTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                ]);
            }

            $order->update(['total' => $total]);
            DB::commit();

            return response()->json($order->load('items.product'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    // Actualizar estado del pedido
    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate(['status' => 'required|string']);

        if ($request->user()->role->name !== 'admin') {
            return response()->json(['message' => 'Solo el admin puede cambiar estados'], 403);
        }

        $order->update(['status' => $data['status']]);

        return response()->json($order);
    }
}
