<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminCategoriesController extends Controller
{
    public function index()
{
    $response = Http::get(url('/api/categories'));

    $categories = $response->json();

    return view('admin.categories.index', compact('categories'));
}


    public function store(Request $request)
    {
        $response = Http::post(url('/api/categories'), $request->only('name'));

        if ($response->failed()) {
            return back()->withErrors('Gagal menambahkan kategori.');
        }

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $response = Http::put(url("/api/categories/$id"), $request->only('name'));

        if ($response->failed()) {
            return back()->withErrors('Gagal mengubah kategori.');
        }

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $response = Http::delete(url("/api/categories/$id"));

        if ($response->failed()) {
            return back()->withErrors('Gagal menghapus kategori.');
        }

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
