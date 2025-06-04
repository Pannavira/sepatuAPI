<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sizes;

class SizesController extends Controller
{
    // Method index yang sudah ada - tidak diubah
    public function index(Request $request)
    {
        $query = Sizes::query();
        
        if ($request->has(key: 'id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('size_label')) {
            $query->where('size_label', $request->size_label);
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('size_label', 'like', '%' . $request->search . '%');
            });
        }
        
        $get = $query->get();

        if ($get->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'value tidak ditemukan'
            ], 404);
        }

        return response()->json($get);
    }

    // CREATE - Menambah data baru
    public function store(Request $request)
    {
        try {
            $request->validate([
                'size_label' => 'required|string|max:255'
            ]);

            $size = new Sizes();
            $size->size_label = $request->size_label;
            $size->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Size berhasil ditambahkan',
                'data' => $size
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan size: ' . $e->getMessage()
            ], 500);
        }
    }

    // READ - Menampilkan data berdasarkan ID
    public function show($id)
    {
        try {
            $size = Sizes::find($id);

            if (!$size) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Size tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $size
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    // UPDATE - Mengupdate data
    public function update(Request $request, $id)
    {
        try {
            $size = Sizes::find($id);

            if (!$size) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Size tidak ditemukan'
                ], 404);
            }

            $request->validate([
                'size_label' => 'required|string|max:255'
            ]);

            $size->size_label = $request->size_label;
            $size->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Size berhasil diupdate',
                'data' => $size
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate size: ' . $e->getMessage()
            ], 500);
        }
    }

    // DELETE - Menghapus data
    public function destroy($id)
    {
        try {
            $size = Sizes::find($id);

            if (!$size) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Size tidak ditemukan'
                ], 404);
            }

            $size->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Size berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus size: ' . $e->getMessage()
            ], 500);
        }
    }
}