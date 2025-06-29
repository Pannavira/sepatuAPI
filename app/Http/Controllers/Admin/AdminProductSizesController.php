<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminProductSizesController extends Controller
{
    public function index()
    {
        $response = Http::get(url('/api/product-sizes?with=product,size'));
        $productSizes = $response->json()['data'] ?? [];

        return view('admin.product_sizes.index', compact('productSizes'));
    }

    public function create()
    {
        $products = Http::get(url('/api/products'))->json();
        $sizes = Http::get(url('/api/sizes'))->json();

        return view('admin.product_sizes.create', compact('products', 'sizes'));
    }

    public function store(Request $request)
    {
        $response = Http::post(url('/api/product-sizes'), $request->all());

        if ($response->failed()) {
            return back()->withErrors('Gagal menambahkan stok per size.');
        }

        return redirect()->route('admin.product-sizes.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $response = Http::delete(url("/api/product-sizes/$id"));

        if ($response->failed()) {
            return back()->withErrors('Gagal menghapus data.');
        }

        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'stock_per_size' => 'required|integer|min:0',
    ]);

    Http::put(url("/api/product-sizes/$id"), [
        'stock_per_size' => $request->stock_per_size
    ]);

    return redirect()->route('admin.product-sizes.index')->with('success', 'stok berhasil diperbarui.');
}

}
