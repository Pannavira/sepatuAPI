<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reviews;
use Illuminate\Support\Facades\Validator;
use Exception;

class ReviewsController extends Controller
{
    /**
     * Display a listing of the resource (READ)
     */
    public function index(Request $request)
    {
        try {
            $query = Reviews::query();
            
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

            if ($request->has('created_at')) {
                $query->whereDate('created_at', $request->created_at);
            }
            
            // Global search
            if ($request->has('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('comment', 'like', '%' . $searchTerm . '%')
                      ->orWhere('rating', $searchTerm)
                      ->orWhere('product_id', $searchTerm)
                      ->orWhere('user_id', $searchTerm)
                      ->orWhere('id', $searchTerm);
                });
            }
            
            // Pagination
            $perPage = $request->get('per_page', 10);
            $reviews = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => $reviews
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource (CREATE)
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|integer',
                'user_id' => 'required|integer',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $review = Reviews::create([
                'product_id' => $request->product_id,
                'user_id' => $request->user_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'created_at' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Review berhasil dibuat',
                'data' => $review
            ], 201);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource (READ by ID)
     */
    public function show($id)
    {
        try {
            $review = Reviews::find($id);

            if (!$review) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Review tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => $review
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource (UPDATE)
     */
    public function update(Request $request, $id)
    {
        try {
            $review = Reviews::find($id);

            if (!$review) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Review tidak ditemukan'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'product_id' => 'sometimes|integer',
                'user_id' => 'sometimes|integer',
                'rating' => 'sometimes|integer|min:1|max:5',
                'comment' => 'sometimes|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $review->update($request->only(['product_id', 'user_id', 'rating', 'comment']));

            return response()->json([
                'status' => 'success',
                'message' => 'Review berhasil diupdate',
                'data' => $review->fresh()
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource (DELETE)
     */
    public function destroy($id)
    {
        try {
            $review = Reviews::find($id);

            if (!$review) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Review tidak ditemukan'
                ], 404);
            }

            $review->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Review berhasil dihapus'
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reviews by product ID
     */
    public function getByProduct($productId)
    {
        try {
            $reviews = Reviews::where('product_id', $productId)
                            ->orderBy('created_at', 'desc')
                            ->get();

            if ($reviews->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Review untuk produk ini tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => $reviews,
                'average_rating' => $reviews->avg('rating')
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reviews by user ID
     */
    public function getByUser($userId)
    {
        try {
            $reviews = Reviews::where('user_id', $userId)
                            ->orderBy('created_at', 'desc')
                            ->get();

            if ($reviews->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Review dari user ini tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => $reviews
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}