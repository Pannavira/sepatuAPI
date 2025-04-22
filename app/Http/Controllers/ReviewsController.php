<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reviews;

class ReviewsController extends Controller
{
    public function index(Request $request)
    {
        $query = Reviews::query();
        
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }
        
        if ($request->has('comment')) {
            $query->where('comment', 'like', '%' . $request->comment . '%');
        }

        if ($request->has('created_at')) {
            $query->where('created_at', $request->created_at);
        }
        
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('comment', 'like', '%' . $request->search . '%')
                  ->orWhere('rating', 'like', '%' . $request->search . '%')
                  ->orWhere('product_id', 'like', '%' . $request->search . '%')
                  ->orWhere('user_id', 'like', '%' . $request->search . '%')
                  ->orWhere('created_at', 'like', '%' . $request->search . '%')
                  ->orWhere('id', 'like', '%' . $request->search . '%');
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