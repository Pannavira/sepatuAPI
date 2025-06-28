<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class AdminProductsController extends Controller
{
    public function index()
{
    $response = Http::get(url('/api/products?with=images,category,sizes.size,brand'));
    $products = $response->json();
    $categories = Http::get(url('/api/categories'))->json();
    $brands = Http::get(url('/api/brands'))->json();

    return view('admin.products.index', compact('products', 'categories', 'brands'));
}

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
{
    $productResponse = Http::post(url('/api/products'), $request->only([
        'name', 'description', 'price', 'stock', 'category_id', 'brand_id'
    ]));

    if ($productResponse->failed()) {
        return back()->withErrors('Gagal menambahkan produk.');
    }

    $product = $productResponse->json();

    // Tambah gambar jika ada
    if ($request->has('image_urls')) {
        foreach ($request->image_urls as $imageUrl) {
            Http::post(url('/api/productimages'), [
                'product_id' => $product['id'],
                'image_url' => $imageUrl
            ]);
        }
    }

    return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
}


    public function edit($id)
{
    $response = Http::get(url("/api/products/$id?with=images,category,sizes.size,brand"));
    $product = $response->successful() ? $response->json() : null;

    $categories = Http::get(url('/api/categories'))->json();
    $brands = Http::get(url('/api/brands'))->json();

    return view('admin.products.edit', compact('product', 'categories', 'brands'));
}

    public function update(Request $request, $id)
{
    // Update data produk utama
    $response = Http::put(url("/api/products/$id"), $request->only([
        'name', 'description', 'price', 'stock', 'category_id', 'brand_id'
    ]));

    if ($response->failed()) {
        return back()->withErrors('Gagal mengupdate produk.');
    }

    // Jika user mengirimkan image_urls baru:
    if ($request->has('image_urls')) {
        // Ambil semua gambar produk
        $imageResponse = Http::get(url("/api/product-images?product_id=$id"));
        $imagesJson = $imageResponse->json();

        // Ambil array gambar dari paginated data
        $existingImages = $imagesJson['data']['data'] ?? [];

        // Hapus semua gambar lama
        foreach ($existingImages as $img) {
            if (isset($img['id'])) {
                Http::delete(url("/api/product-images/{$img['id']}"));
            }
        }

        // Tambahkan gambar baru (berbasis URL)
        foreach ($request->image_urls as $imageUrl) {
            Http::post(url("/api/product-images"), [
                'product_id' => $id,
                'image_url' => $imageUrl
            ]);
        }
    }

    return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
}


    public function destroy($id)
    {
        $response = Http::delete(url('/api/products/' . $id));

        if ($response->failed()) {
            return back()->withErrors('Gagal menghapus produk.');
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
