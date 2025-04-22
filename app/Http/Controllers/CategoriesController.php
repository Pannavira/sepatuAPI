<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories; // atau App\Models\Category kalau kamu ganti nama model

class CategoriesController extends Controller 
{
    public function index(Request $request)
    {
        $query = Categories::query();
        
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }
                
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('desc', 'like', '%' . $request->search . '%'); // pastikan nama field benar
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
