<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;

class OrdersController extends Controller
{
    /**
     * Display a listing of orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Orders::query();
        
        // Filter by ID if provided
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }
        
        // Filter by user_id if provided
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by payment_status if provided
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Filter by payment_method if provided
        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        // Filter by date range if provided
        if ($request->has('start_date')) {
            $query->where('order_date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date')) {
            $query->where('order_date', '<=', $request->end_date);
        }
        
        // Filter by price range if provided
        if ($request->has('min_price')) {
            $query->where('total_price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price')) {
            $query->where('total_price', '<=', $request->max_price);
        }
        
        // Search by shipping address
        if ($request->has('address')) {
            $query->where('shipping_address', 'like', '%' . $request->address . '%');
        }
        
        $orders = $query->get();
        
        if ($orders->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Orders not found'
            ], 404);
        }
        
        return response()->json($orders);
    }
    
    /**
     * Display the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Orders::find($id);
        
        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }
        
        return response()->json($order);
    }
}