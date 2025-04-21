<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brands;

class BrandsController extends Controller
{
    public function index(Request $request){
        $query = Brands::query();


        if ($request->has('name')) {
            $query->where('name', $request->name);
        }

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('description')) {
            $query->where('description', $request->description);
        }
        

        //bagian ini buat filter, jadi nanti yang diubah hanya bagian atas, sesuaiin sama database aja.
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
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
