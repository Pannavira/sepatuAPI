<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminSizeController extends Controller
{
    public function index()
    {
        $sizes = Http::get(url('/api/sizes'))->json();
        return view('admin.sizes.index', compact('sizes'));
    }

    public function store(Request $request)
    {
        $response = Http::post(url('/api/sizes'), $request->only('size_label'));
        return redirect()->route('admin.sizes.index')->with('success', 'Ukuran berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $response = Http::put(url("/api/sizes/{$id}"), $request->only('size_label'));
        return redirect()->route('admin.sizes.index')->with('success', 'Ukuran berhasil diperbarui');
    }

    public function destroy($id)
    {
        $response = Http::delete(url("/api/sizes/{$id}"));
        return redirect()->route('admin.sizes.index')->with('success', 'Ukuran berhasil dihapus');
    }
}
