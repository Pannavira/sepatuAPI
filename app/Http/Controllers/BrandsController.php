<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brands;

class BrandsController extends Controller
{
    // READ - Get all brands with optional filters
    public function index(Request $request){
        $query = Brands::query();

        if ($request->has('name')) {
            $query->where('name', $request->name);
        }

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('description')) {
            $query->where('description', $request->description);
        }

        // Global search filter
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
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

    // CREATE - Store a new brand
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $brand = Brands::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Brand created successfully',
            'data' => $brand
        ], 201);
    }

    // READ - Show a single brand by ID
    public function show($id)
    {
        $brand = Brands::find($id);

        if (!$brand) {
            return response()->json([
                'status' => 'error',
                'message' => 'Brand not found'
            ], 404);
        }

        return response()->json($brand);
    }

    // UPDATE - Update an existing brand
    public function update(Request $request, $id)
    {
        $brand = Brands::find($id);

        if (!$brand) {
            return response()->json([
                'status' => 'error',
                'message' => 'Brand not found'
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $brand->update($request->only(['name', 'description']));

        return response()->json([
            'status' => 'success',
            'message' => 'Brand updated successfully',
            'data' => $brand
        ]);
    }

    // DELETE - Delete a brand
    public function destroy($id)
    {
        $brand = Brands::find($id);

        if (!$brand) {
            return response()->json([
                'status' => 'error',
                'message' => 'Brand not found'
            ], 404);
        }

        $brand->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Brand deleted successfully'
        ]);
    }
}