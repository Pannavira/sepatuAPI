<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;

class ProductsController extends Controller
{
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
}