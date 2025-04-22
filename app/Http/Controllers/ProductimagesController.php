<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductImages;

class ProductImageController extends Controller
{
    public function get(Request $request)
    {
        $query = ProductImage::query();
        
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }
        
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        $productImages = $query->get();
        
        if ($productImages->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No product images found'
            ], 404);
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $productImages
        ]);
    }
    
    // Search product images by image URL
    public function search(Request $request)
    {
        if (!$request->has('keyword')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Search keyword is required'
            ], 400);
        }
        
        $keyword = $request->keyword;
        
        $productImages = ProductImage::where('image_url', 'like', '%' . $keyword . '%')->get();
        
        if ($productImages->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No product images found matching your search criteria'
            ], 404);
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $productImages
        ]);
    }
}