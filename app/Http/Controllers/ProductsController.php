<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    // READ - Get all products or filtered products
    public function index(Request $request)
    {
        $query = Products::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }
        
        if ($request->has('name')) {
            $query->where('name', $request->name);
        }
        
        if ($request->has('description')) {
            $query->where('description', $request->description);
        }

        if ($request->has('price')) {
            $query->where('price', $request->price);
        }

        if ($request->has('stock')) {
            $query->where('stock', $request->stock);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->has('created_at')) {
            $query->where('created_at', $request->created_at);
        }

        if ($request->has('updated_at')) {
            $query->where('updated_at', $request->updated_at);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('price', 'like', '%' . $request->search . '%')
                  ->orWhere('stock', 'like', '%' . $request->search . '%')
                  ->orWhere('category_id', 'like', '%' . $request->search . '%')
                  ->orWhere('brand_id', 'like', '%' . $request->search . '%')
                  ->orWhere('created_at', 'like', '%' . $request->search . '%')
                  ->orWhere('updated_at', 'like', '%' . $request->search . '%');
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

    // READ - Get single product by ID
    public function show($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $product
        ]);
    }

    // CREATE - Store new product
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $product = Products::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Product berhasil ditambahkan',
                'data' => $product
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // UPDATE - Update existing product
    public function update(Request $request, $id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'category_id' => 'sometimes|required|integer',
            'brand_id' => 'sometimes|required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $product->update($request->only([
                'name', 'description', 'price', 'stock', 'category_id', 'brand_id'
            ]));

            return response()->json([
                'status' => 'success',
                'message' => 'Product berhasil diupdate',
                'data' => $product->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE - Delete product
    public function destroy($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product tidak ditemukan'
            ], 404);
        }

        try {
            $product->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Product berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}