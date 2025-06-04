<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use Illuminate\Validation\Rule;

class OrdersController extends Controller
{
    // READ - Get all orders or filter by parameters
    public function index(Request $request)
    {
        $query = Orders::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('order_date')) {
            $query->where('order_date', $request->order_date);
        }

        if ($request->has('total_price')) {
            $query->where('total_price', $request->total_price);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('shipping_address')) {
            $query->where('shipping_address', 'like', '%' . $request->shipping_address . '%');
        }

        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('user_id', 'like', '%' . $request->search . '%')
                  ->orWhere('order_date', 'like', '%' . $request->search . '%')
                  ->orWhere('total_price', 'like', '%' . $request->search . '%')
                  ->orWhere('status', 'like', '%' . $request->search . '%')
                  ->orWhere('shipping_address', 'like', '%' . $request->search . '%')
                  ->orWhere('payment_method', 'like', '%' . $request->search . '%')
                  ->orWhere('payment_status', 'like', '%' . $request->search . '%');
            });
        }

        $get = $query->get();

        if ($get->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'value tidak ditemukan'
            ], 404);
        }

        return response()->json($get);
    }

    // READ - Get single order by ID
    public function show($id)
    {
        $order = Orders::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order tidak ditemukan'
            ], 404);
        }

        return response()->json($order);
    }

    // CREATE - Add new order
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'order_date' => 'required|date',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:paid,pending,shipped',
            'shipping_address' => 'required|string|max:255',
            'payment_method' => 'required|in:Transfer Bank,COD,OVO',
            'payment_status' => 'required|in:paid,unpaid'
        ]);

        try {
            $order = Orders::create([
                'user_id' => $request->user_id,
                'order_date' => $request->order_date,
                'total_price' => $request->total_price,
                'status' => $request->status,
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Order berhasil dibuat',
                'data' => $order
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat order: ' . $e->getMessage()
            ], 500);
        }
    }

    // UPDATE - Update existing order
    public function update(Request $request, $id)
    {
        $order = Orders::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'user_id' => 'sometimes|required|integer',
            'order_date' => 'sometimes|required|date',
            'total_price' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|required|in:paid,pending,shipped',
            'shipping_address' => 'sometimes|required|string|max:255',
            'payment_method' => 'sometimes|required|in:Transfer Bank,COD,OVO',
            'payment_status' => 'sometimes|required|in:paid,unpaid'
        ]);

        try {
            $order->update($request->only([
                'user_id',
                'order_date', 
                'total_price',
                'status',
                'shipping_address',
                'payment_method',
                'payment_status'
            ]));

            return response()->json([
                'status' => 'success',
                'message' => 'Order berhasil diupdate',
                'data' => $order
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate order: ' . $e->getMessage()
            ], 500);
        }
    }

    // DELETE - Delete order
    public function destroy($id)
    {
        $order = Orders::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order tidak ditemukan'
            ], 404);
        }

        try {
            $order->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Order berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus order: ' . $e->getMessage()
            ], 500);
        }
    }

    // BULK DELETE - Delete multiple orders
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:orders,id'
        ]);

        try {
            $deletedCount = Orders::whereIn('id', $request->ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => $deletedCount . ' order berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus orders: ' . $e->getMessage()
            ], 500);
        }
    }
}