<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductImages;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProductImages::query();
        
        // Filter by specific fields
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }
        
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->has('image_url')) {
            $query->where('image_url', 'like', '%' . $request->image_url . '%');
        }

        // Global search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('product_id', 'like', '%' . $request->search . '%')
                  ->orWhere('image_url', 'like', '%' . $request->search . '%');
            });
        }
        
        // Pagination
        $perPage = $request->get('per_page', 15);
        $productImages = $query->paginate($perPage);

        if ($productImages->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diambil',
            'data' => $productImages
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'image_url' => 'required|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->only(['product_id', 'image_url']);

            // Handle file upload if present
            if ($request->hasFile('image_file')) {
                $file = $request->file('image_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('images', $filename, 'public');
                $data['image_url'] = 'images/' . $filename;
            }

            $productImage = ProductImages::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'data' => $productImage
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $productImage = ProductImages::findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => $productImage
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'sometimes|required|integer|exists:products,id',
            'image_url' => 'sometimes|required|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $productImage = ProductImages::findOrFail($id);
            $data = $request->only(['product_id', 'image_url']);

            // Handle file upload if present
            if ($request->hasFile('image_file')) {
                // Delete old file if exists
                if ($productImage->image_url && Storage::disk('public')->exists($productImage->image_url)) {
                    Storage::disk('public')->delete($productImage->image_url);
                }

                $file = $request->file('image_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('images', $filename, 'public');
                $data['image_url'] = 'images/' . $filename;
            }

            $productImage->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diupdate',
                'data' => $productImage
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $productImage = ProductImages::findOrFail($id);
            
            // Delete associated file if exists
            if ($productImage->image_url && Storage::disk('public')->exists($productImage->image_url)) {
                Storage::disk('public')->delete($productImage->image_url);
            }

            $productImage->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get images by product ID
     */
    public function getByProductId($productId)
    {
        try {
            $productImages = ProductImages::where('product_id', $productId)->get();

            if ($productImages->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada gambar untuk produk ini'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => $productImages
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete images
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:product_images,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $productImages = ProductImages::whereIn('id', $request->ids)->get();
            
            // Delete associated files
            foreach ($productImages as $productImage) {
                if ($productImage->image_url && Storage::disk('public')->exists($productImage->image_url)) {
                    Storage::disk('public')->delete($productImage->image_url);
                }
            }

            $deletedCount = ProductImages::whereIn('id', $request->ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => "Berhasil menghapus {$deletedCount} data"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteByProduct($productId)
{
    ProductImages::where('product_id', $productId)->delete();

    return response()->json(['message' => 'Semua gambar produk berhasil dihapus.']);
}
}