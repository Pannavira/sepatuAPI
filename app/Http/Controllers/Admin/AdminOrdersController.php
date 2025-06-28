<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AdminOrdersController extends Controller
{
    public function index()
    {
        $response = Http::get(url('/api/orders?with=user,order_items.product'));
        $orders = $response->json()['data'] ?? [];

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $response = Http::get(url("/api/orders/$id?with=user,order_items.product"));
        $order = $response->json();

        return view('admin.orders.show', compact('order'));
    }
}
