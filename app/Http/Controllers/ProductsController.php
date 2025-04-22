<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        
        if ($request->has('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
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
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        $products = $query->get();
        
        if ($products->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Products not found'
            ], 404);
        }
        
        return response()->json($products);
    }
    
    public function show($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }
        
        return response()->json($product);
    }
}