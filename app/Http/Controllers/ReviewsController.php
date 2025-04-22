<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewsController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::query();
        
        // Filter by specific fields
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
        
        // General search across multiple columns
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('comment', 'like', '%' . $request->search . '%')
                  ->orWhere('rating', 'like', '%' . $request->search . '%');
            });
        }
        
        $reviews = $query->get();
        
        if ($reviews->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Reviews not found'
            ], 404);
        }
        
        return response()->json($reviews);
    }
    
    public function show($id)
    {
        $review = Review::find($id);
        
        if (!$review) {
            return response()->json([
                'status' => 'error',
                'message' => 'Review not found'
            ], 404);
        }
        
        return response()->json($review);
    }
}