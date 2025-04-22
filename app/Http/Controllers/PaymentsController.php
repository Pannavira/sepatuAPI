<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payments;

class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        $query = Payments::query();
        
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }
        
        if ($request->has('order_id')) {
            $query->where('order_id', $request->order_id);
        }

        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        if ($request->has('start_date')) {
            $query->where('payment_date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date')) {
            $query->where('payment_date', '<=', $request->end_date);
        }
        
        if ($request->has('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        
        if ($request->has('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }
        
        $payments = $query->get();
        
        if ($payments->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payments not found'
            ], 404);
        }
        
        return response()->json($payments);
    }
    
    public function show($id)
    {
        $payment = Payments::find($id);
        
        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found'
            ], 404);
        }
        
        return response()->json($payment);
    }
}