<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sizes;

class SizesController extends Controller
{
    public function index(Request $request)
    {
        $query = Sizes::query();
        
        if ($request->has(key: 'id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('size_label')) {
            $query->where('size_label', $request->size_label);
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('size_label', 'like', '%' . $request->search . '%');
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