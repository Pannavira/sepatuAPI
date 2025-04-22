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
        
        if ($request->has('quantity')) {
            $query->where('quantity', $request->quantity);
        }

        if ($request->has('quantity')) {
            $query->where('quantity', $request->quantity);
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('order_id', 'like', '%' . $request->search . '%')
                  ->orWhere('product_id', 'like', '%' . $request->search . '%')
                  ->orWhere('size_id', 'like', '%' . $request->search . '%')
                  ->orWhere('quantity', 'like', '%' . $request->search . '%');
            });
        }
        
        $get = $query->get();

        if ($get->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'value tidak ditemukan'], 404);
            }

        return response()->json($get);
    }
}