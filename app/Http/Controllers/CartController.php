<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Products;
use App\Models\Sizes;
use Illuminate\Support\Facades\Auth;

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
            $query->where(function ($q) use ($request) {
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

    public function showCartPage()
{
    $userId = Auth::id();

    // Ambil keranjang user
    $cart = Cart::firstOrCreate(['user_id' => $userId]);

    // Ambil item keranjang dengan relasi lengkap
    $cartItems = \App\Models\CartItems::with([
        'product.images',          // untuk gambar
        'product.sizes.size',      // untuk ambil product_size -> size
        'size'                     // untuk ambil size dari cart_items
    ])
    ->where('cart_id', $cart->id)
    ->get();

    return view('cart.index', compact('cartItems'));
}

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $existingItem = CartItems::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->where('size_id', $request->size_id)
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
        } else {
            CartItems::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'size_id' => $request->size_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('cart.show')->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function updateCartItem(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $item = CartItems::findOrFail($id);
        $item->quantity = $request->quantity;
        $item->size_id = $request->size_id;
        $item->save();

        return redirect()->route('cart.show')->with('success', 'Jumlah produk diperbarui.');
    }

    public function delete($id)
    {
        $item = CartItems::findOrFail($id);
        $item->delete();

        return redirect()->route('cart.show')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
