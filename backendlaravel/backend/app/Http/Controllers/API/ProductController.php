<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Listar productos (paginado + búsqueda)
    public function index(Request $request)
    {
        $query = $request->query('q');
        $products = Product::with('user', 'category')
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                  ->orWhere('description', 'like', "%$query%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($products);
    }

    // Ver producto individual
    public function show(Product $product)
    {
        return response()->json($product->load('user', 'category'));
    }

    // Crear producto
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['user_id'] = $request->user()->id;
        $product = Product::create($data);

        return response()->json($product, 201);
    }

    // Actualizar producto
    public function update(Request $request, Product $product)
    {
        if ($product->user_id !== $request->user()->id && $request->user()->role->name !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return response()->json($product);
    }

    // Eliminar producto
    public function destroy(Product $product, Request $request)
    {
        if ($product->user_id !== $request->user()->id && $request->user()->role->name !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Producto eliminado correctamente']);
    }
}
