<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItems;

class OrderItemsController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderItems::query();

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
        
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        if ($request->has('quantity')) {
            $query->where('quantity', $request->quantity);
        }
        
        $orderItems = $query->get();
        
        if ($orderItems->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order items not found'
            ], 404);
        }
        
        return response()->json($orderItems);
    }
    
    public function show($id)
    {
        $orderItem = OrderItems::find($id);
        
        if (!$orderItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order item not found'
            ], 404);
        }
        
        return response()->json($orderItem);
    }
}