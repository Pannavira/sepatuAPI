<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItems;

class CartItemsController extends Controller
{
    // GET /api/cart-items
    public function index(Request $request)
    {
        $query = CartItems::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('cart_id')) {
            $query->where('cart_id', $request->cart_id);
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

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('cart_id', 'like', '%' . $request->search . '%')
                  ->orWhere('product_id', 'like', '%' . $request->search . '%')
                  ->orWhere('size_id', 'like', '%' . $request->search . '%')
                  ->orWhere('quantity', 'like', '%' . $request->search . '%');
            });
        }

        $items = $query->get();

        if ($items->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'value tidak ditemukan'
            ], 404);
        }

        return response()->json($items);
    }

    // POST /api/cart-items
    public function store(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|integer',
            'product_id' => 'required|integer',
            'size_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItems::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Cart item created successfully',
            'data' => $item
        ], 201);
    }

    // GET /api/cart-items/{id}
    public function show($id)
    {
        $item = CartItems::find($id);

        if (!$item) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart item not found'
            ], 404);
        }

        return response()->json($item);
    }

    // PUT/PATCH /api/cart-items/{id}
    public function update(Request $request, $id)
    {
        $item = CartItems::find($id);

        if (!$item) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart item not found'
            ], 404);
        }

        $request->validate([
            'cart_id' => 'sometimes|integer',
            'product_id' => 'sometimes|integer',
            'size_id' => 'sometimes|integer',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $item->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Cart item updated successfully',
            'data' => $item
        ]);
    }

    // DELETE /api/cart-items/{id}
    public function destroy($id)
    {
        $item = CartItems::find($id);

        if (!$item) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart item not found'
            ], 404);
        }

        $item->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Cart item deleted successfully'
        ]);
    }
}
