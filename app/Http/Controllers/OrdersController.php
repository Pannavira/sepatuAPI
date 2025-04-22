<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;

class OrdersController extends Controller
{
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
}
