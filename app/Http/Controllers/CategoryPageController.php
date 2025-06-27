<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Categories;
use App\Models\Products;

class CategoryPageController extends Controller
{
    public function show($id)
    {
        $category = Categories::findOrFail($id);
        $products = Products::where('category_id', $id)->with('images')->get();

        return view('category.show', compact('category', 'products'));
    }
}
