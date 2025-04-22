<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function index(Request $request){
        $query = Cart::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('user_id', 'like', '%' . $request->search . '%')
                  ->orwhere('id', 'like', '%' . $request->search . '%');
            });
        }


        $get = $query->get();

        if ($get->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'value tidak ditemukan'], 404);
            }

        return response()->json($get);
    }
}
