<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    // Tampilkan semua data pembayaran
    public function index()
    {
        $payments = Payments::all();
        return response()->json($payments);
    }

    // Simpan data pembayaran baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'payment_status' => 'required|string|max:255',
        ]);

        $payment = Payments::create($validated);
        return response()->json($payment, 201);
    }

    // Tampilkan satu data pembayaran
    public function show($id)
    {
        $payment = Payments::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }
        return response()->json($payment);
    }

    // Update data pembayaran
    public function update(Request $request, $id)
    {
        $payment = Payments::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $validated = $request->validate([
            'order_id' => 'sometimes|integer|exists:orders,id',
            'payment_date' => 'sometimes|date',
            'payment_method' => 'sometimes|string|max:255',
            'amount' => 'sometimes|numeric',
            'payment_status' => 'sometimes|string|max:255',
        ]);

        $payment->update($validated);
        return response()->json($payment);
    }

    // Hapus data pembayaran
    public function destroy($id)
    {
        $payment = Payments::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $payment->delete();
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
