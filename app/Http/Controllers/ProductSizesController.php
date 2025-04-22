<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductSize;

class ProductSizesController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductSize::query();
        
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->has('size_id')) {
            $query->where('size_id', $request->size_id);
        }
        
        if ($request->has('stock_per_size')) {
            $query->where('stock_per_size', $request->stock_per_size);
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('product_id', 'like', '%' . $request->search . '%')
                  ->orWhere('size_id', 'like', '%' . $request->search . '%')
                  ->orWhere('stock_per_size', 'like', '%' . $request->search . '%');
            });
        }
        
        $productSizes = $query->get();
        
        if ($productSizes->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product sizes not found'
            ], 404);
        }
        
        return response()->json($productSizes);
    }
    
    public function show($id)
    {
        $productSize = ProductSize::find($id);
        
        if (!$productSize) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product size not found'
            ], 404);
        }
        
        return response()->json($productSize);
    }
}