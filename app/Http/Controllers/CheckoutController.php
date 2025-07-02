<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders; // Gunakan Orders bukan Order
use App\Models\OrderItems; // Gunakan OrderItems bukan OrderItem
use App\Models\CartItems; // Gunakan CartItems bukan CartItem
use App\Models\Cart;
use App\Models\Payments; // Gunakan Payments bukan Payment
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menampilkan halaman checkout
    public function index()
    {
        $user = Auth::user();

        $cartItems = CartItems::with(['product.images', 'size'])
            ->whereHas('cart', fn ($q) => $q->where('user_id', $user->id))
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Keranjang Anda kosong.');
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->quantity * $item->product->price);
        $shippingCost = 15000;
        $total = $subtotal + $shippingCost;

        return view('checkout.index', compact('cartItems', 'subtotal', 'shippingCost', 'total'));
    }

    // Proses checkout
    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|in:Transfer Bank,COD,OVO',
        ]);

        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return redirect()->route('cart.show')->with('error', 'Keranjang tidak ditemukan.');
        }

        $cartItems = CartItems::with(['product', 'size'])->where('cart_id', $cart->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Keranjang kosong.');
        }

        DB::beginTransaction();

        try {
            $subtotal = $cartItems->sum(fn ($item) => $item->quantity * $item->product->price);
            $shippingCost = 15000;
            $total = $subtotal + $shippingCost;

            // Buat order dengan model yang benar - hanya gunakan unpaid/paid
            $order = Orders::create([
                'user_id' => $user->id,
                'order_date' => now(),
                'total_price' => $total,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
                'payment_status' => 'unpaid', // Ubah dari 'pending' ke 'unpaid'
            ]);

            // Tambahkan log untuk debugging
            \Log::info('Order created:', ['order_id' => $order->id, 'user_id' => $user->id]);

            // Buat order items
            foreach ($cartItems as $item) {
                $orderItem = OrderItems::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'size_id' => $item->size_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);

                \Log::info('Order item created:', ['order_item_id' => $orderItem->id ?? 'failed']);
            }

            // Buat payment record
            $payment = Payments::create([
                'order_id' => $order->id,
                'payment_date' => now(),
                'payment_method' => $request->payment_method,
                'amount' => $total,
                'payment_status' => 'unpaid' // Ubah dari 'pending' ke 'unpaid'
            ]);

            \Log::info('Payment created:', ['payment_id' => $payment->id ?? 'failed']);

            // Hapus cart items setelah order berhasil
            CartItems::where('cart_id', $cart->id)->delete();

            DB::commit();

            \Log::info('Checkout completed successfully', ['order_id' => $order->id]);

            return redirect()->route('checkout.success', $order->id)->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Checkout failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage())->withInput();
        }
    }

    // Halaman sukses
    public function success($orderId)
    {
        $order = Orders::with(['orderItems.product', 'orderItems.size', 'payment'])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }

    // Detail pesanan user
    public function orderDetail($orderId)
    {
        $order = Orders::with(['orderItems.product.images', 'orderItems.size', 'payment'])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('orders.detail', compact('order'));
    }

    // Admin: update status pembayaran - hanya paid/unpaid
public function updatePaymentStatus(Request $request, $orderId)
{
    $request->validate([
        'payment_status' => 'required|in:unpaid,paid'
    ]);

    DB::beginTransaction();

    try {
        $order = Orders::findOrFail($orderId);
        $payment = Payments::where('order_id', $orderId)->firstOrFail();

        $payment->update([
            'payment_status' => $request->payment_status,
            'payment_date' => $request->payment_status === 'paid' ? now() : $payment->payment_date
        ]);

        $order->update([
            'status' => $request->payment_status === 'paid' ? 'paid' : 'pending',
            'payment_status' => $request->payment_status
        ]);

        DB::commit();

        return response()->json(['message' => 'Status pembayaran berhasil diperbarui.']);
    } catch (\Throwable $e) {
        DB::rollBack();
        return response()->json(['error' => 'Gagal memperbarui status: ' . $e->getMessage()], 500);
    }
}
}