<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    // GET /api/carts
    public function index(Request $request)
    {
        $query = Cart::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('user_id', 'like', '%' . $request->search . '%')
                  ->orWhere('id', 'like', '%' . $request->search . '%');
            });
        }

        $carts = $query->get();

        if ($carts->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'value tidak ditemukan'
            ], 404);
        }

        return response()->json($carts);
    }

    // POST /api/carts
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer'
        ]);

        $cart = Cart::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Cart created successfully',
            'data' => $cart
        ], 201);
    }

    // GET /api/carts/{id}
    public function show($id)
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart not found'
            ], 404);
        }

        return response()->json($cart);
    }

    // PUT/PATCH /api/carts/{id}
    public function update(Request $request, $id)
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart not found'
            ], 404);
        }

        $request->validate([
            'user_id' => 'sometimes|integer',
            'product_id' => 'sometimes|integer',
            'quantity' => 'sometimes|integer|min:1'
        ]);

        $cart->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Cart updated successfully',
            'data' => $cart
        ]);
    }

    // DELETE /api/carts/{id}
    public function destroy($id)
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart not found'
            ], 404);
        }

        $cart->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Cart deleted successfully'
        ]);
    }
}
