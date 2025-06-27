<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;

class HomeController extends Controller
{
    public function index()
{
    $products = Products::with('images', 'reviews') // pastikan relasi review dimuat
        ->get()
        ->map(function ($product) {
            $product->average_rating = $product->reviews->avg('rating') ?? 0;
            $product->review_count = $product->reviews->count();
            return $product;
        });

    return view('welcome', compact('products'));
}

}
