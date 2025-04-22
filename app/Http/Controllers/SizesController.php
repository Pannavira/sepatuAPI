<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Size;

class SizesController extends Controller
{
    public function index(Request $request)
    {
        $query = Size::query();
        
        // Filter by specific fields
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('size_label')) {
            $query->where('size_label', $request->size_label);
        }
        
        // General search across columns
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('size_label', 'like', '%' . $request->search . '%');
            });
        }
        
        $sizes = $query->get();
        
        if ($sizes->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sizes not found'
            ], 404);
        }
        
        return response()->json($sizes);
    }
    
    public function show($id)
    {
        $size = Size::find($id);
        
        if (!$size) {
            return response()->json([
                'status' => 'error',
                'message' => 'Size not found'
            ], 404);
        }
        
        return response()->json($size);
    }
}