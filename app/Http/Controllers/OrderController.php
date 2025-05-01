<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    // Tampilkan semua order
    public function index()
    {
        $orders = Order::all();
        return response()->json([
            'success' => true,
            'data' => $orders
        ], 200);
    }

    // Tampilkan order berdasarkan ID
    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $order
        ], 200);
    }

    // Buat order baru dengan validasi user dan product via API
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        // Validasi user via UserService API
        $userResponse = Http::get('http://localhost:8002/api/users/' . $request->user_id);
        if ($userResponse->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan di UserService'
            ], 422);
        }

        // Validasi product via ProductService API
        $productResponse = Http::get('http://localhost:8000/api/products/' . $request->product_id);
        if ($productResponse->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan di ProductService'
            ], 422);
        }

        $productData = $productResponse->json();

        if (!isset($productData['data']['price'])) {
            return response()->json([
                'success' => false,
                'message' => 'Data harga produk tidak ditemukan'
            ], 422);
        }

        $totalPrice = $productData['data']['price'] * $request->quantity;

        // Simpan order
        $order = Order::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order berhasil dibuat',
            'data' => $order
        ], 201);
    }

    // Update order (full/partial)
    public function update(Request $request, $id)
{
    $order = Order::find($id);
    if (!$order) {
        return response()->json([
            'success' => false,
            'message' => 'Order tidak ditemukan'
        ], 404);
    }

    $request->validate([
        'user_id' => 'sometimes|integer',
        'product_id' => 'sometimes|integer',
        'quantity' => 'sometimes|integer|min:1',
        'status' => 'sometimes|string',
    ]);

    if ($request->has('user_id')) {
        $userResponse = Http::get('http://localhost:8002/api/users/' . $request->user_id);
        if ($userResponse->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan di UserService'
            ], 422);
        }
    }

    if ($request->has('product_id')) {
        $productResponse = Http::get('http://localhost:8000/api/products/' . $request->product_id);
        if ($productResponse->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan di ProductService'
            ], 422);
        }
        $productData = $productResponse->json();

        if (!isset($productData['data']['price'])) {
            return response()->json([
                'success' => false,
                'message' => 'Data harga produk tidak ditemukan'
            ], 422);
        }

        $quantity = $request->quantity ?? $order->quantity;
        $request->merge(['total_price' => $productData['data']['price'] * $quantity]);
    }

    if ($request->has('quantity') && !$request->has('product_id')) {
        $productResponse = Http::get('http://localhost:8000/api/products/' . $order->product_id);
        if ($productResponse->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan di ProductService'
            ], 422);
        }
        $productData = $productResponse->json();

        if (!isset($productData['data']['price'])) {
            return response()->json([
                'success' => false,
                'message' => 'Data harga produk tidak ditemukan'
            ], 422);
        }

        $request->merge(['total_price' => $productData['data']['price'] * $request->quantity]);
    }

    $order->update($request->all());

    return response()->json([
        'success' => true,
        'message' => 'Order berhasil diupdate',
        'data' => $order
    ], 200);
}

    // Hapus order
    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak ditemukan'
            ], 404);
        }

        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order berhasil dihapus'
        ], 200);
    }
}
