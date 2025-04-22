<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductImages;

class ProductImagesController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductImages::query();
        
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }
        
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->has('image_url')) {
            $query->where('image_url', $request->image_url);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('product_id', 'like', '%' . $request->search . '%')
                  ->orWhere('image_url', 'like', '%' . $request->search . '%');
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