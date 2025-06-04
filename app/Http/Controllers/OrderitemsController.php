<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItems;
use Illuminate\Support\Facades\Validator;

class OrderItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = OrderItems::query();

        // Filter by specific fields
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('order_id')) {
            $query->where('order_id', $request->order_id);
        }

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->has('size_id')) {
            $query->where('size_id', $request->size_id);
        }
        
        if ($request->has('quantity')) {
            $query->where('quantity', $request->quantity);
        }

        if ($request->has('price')) {
            $query->where('price', $request->price);
        }
        
        // Global search functionality
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('order_id', 'like', '%' . $request->search . '%')
                  ->orWhere('product_id', 'like', '%' . $request->search . '%')
                  ->orWhere('size_id', 'like', '%' . $request->search . '%')
                  ->orWhere('quantity', 'like', '%' . $request->search . '%')
                  ->orWhere('price', 'like', '%' . $request->search . '%');
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $orderItems = $query->paginate($perPage);

        if ($orderItems->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data order items tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $orderItems
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id',
            'product_id' => 'required|integer|exists:products,id',
            'size_id' => 'required|integer|exists:sizes,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $orderItem = OrderItems::create([
                'order_id' => $request->order_id,
                'product_id' => $request->product_id,
                'size_id' => $request->size_id,
                'quantity' => $request->quantity,
                'price' => $request->price
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Order item berhasil dibuat',
                'data' => $orderItem
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat order item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $orderItem = OrderItems::findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'data' => $orderItem
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order item tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $orderItem = OrderItems::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'order_id' => 'sometimes|required|integer|exists:orders,id',
                'product_id' => 'sometimes|required|integer|exists:products,id',
                'size_id' => 'sometimes|required|integer|exists:sizes,id',
                'quantity' => 'sometimes|required|integer|min:1',
                'price' => 'sometimes|required|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $orderItem->update($request->only([
                'order_id', 'product_id', 'size_id', 'quantity', 'price'
            ]));

            return response()->json([
                'status' => 'success',
                'message' => 'Order item berhasil diupdate',
                'data' => $orderItem->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order item tidak ditemukan atau gagal diupdate'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $orderItem = OrderItems::findOrFail($id);
            $orderItem->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Order item berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order item tidak ditemukan atau gagal dihapus'
            ], 404);
        }
    }

    /**
     * Get order items by order ID
     */
    public function getByOrderId($orderId)
    {
        try {
            $orderItems = OrderItems::where('order_id', $orderId)->get();
            
            if ($orderItems->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada order items untuk order ini'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $orderItems
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data order items'
            ], 500);
        }
    }

    /**
     * Bulk delete order items
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:order_items,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $deletedCount = OrderItems::whereIn('id', $request->ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => "Berhasil menghapus {$deletedCount} order items"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus order items',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}