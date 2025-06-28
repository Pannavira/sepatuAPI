<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductSizes;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProductSizesController extends Controller
{
    /**
     * Display the specified resource (READ by ID)
     */

    public function index()
{
    try {
        $productSizes = ProductSizes::with(['product', 'size'])->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diambil',
            'data' => $productSizes
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}
    public function show($product_size) // Ganti dari $id ke $product_size
    {
        try {
            $productSize = ProductSizes::find($product_size);

            if (!$productSize) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product size tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => $productSize
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource (UPDATE)
     */
    public function update(Request $request, $product_size) // Ganti dari $id ke $product_size
    {
        try {
            $productSize = ProductSizes::find($product_size);

            if (!$productSize) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product size tidak ditemukan'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'product_id' => 'sometimes|integer',
                'size_id' => 'sometimes|integer',
                'stock_per_size' => 'sometimes|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if new combination already exists (if product_id or size_id is being updated)
            if ($request->has('product_id') || $request->has('size_id')) {
                $newProductId = $request->get('product_id', $productSize->product_id);
                $newSizeId = $request->get('size_id', $productSize->size_id);
                
                $exists = ProductSizes::where('product_id', $newProductId)
                                     ->where('size_id', $newSizeId)
                                     ->where('id', '!=', $product_size) // Ganti $id ke $product_size
                                     ->exists();

                if ($exists) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Kombinasi product_id dan size_id sudah ada'
                    ], 409);
                }
            }

            $productSize->update($request->only(['product_id', 'size_id', 'stock_per_size']));

            return response()->json([
                'status' => 'success',
                'message' => 'Product size berhasil diupdate',
                'data' => $productSize->fresh()
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource (DELETE)
     */
    public function destroy($product_size) // Ganti dari $id ke $product_size
    {
        try {
            $productSize = ProductSizes::find($product_size);

            if (!$productSize) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product size tidak ditemukan'
                ], 404);
            }

            $productSize->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Product size berhasil dihapus'
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteByProduct($productId)
{
    ProductSizes::where('product_id', $productId)->delete();

    return response()->json(['message' => 'Semua ukuran produk berhasil dihapus.']);
}

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'product_id' => 'required|exists:products,id',
        'size_id' => 'required|exists:sizes,id',
        'stock_per_size' => 'required|integer|min:0'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    // Cek apakah kombinasi sudah ada
    $exists = ProductSizes::where('product_id', $request->product_id)
                          ->where('size_id', $request->size_id)
                          ->exists();

    if ($exists) {
        return response()->json([
            'status' => 'error',
            'message' => 'Kombinasi product_id dan size_id sudah ada'
        ], 409);
    }

    $productSize = ProductSizes::create([
        'product_id' => $request->product_id,
        'size_id' => $request->size_id,
        'stock_per_size' => $request->stock_per_size
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Stok per ukuran berhasil ditambahkan',
        'data' => $productSize
    ], 201);
}
}