<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Login;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $query = Login::query();
        
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('username')) {
            $query->where('username', $request->username);
        }

        if ($request->has('last_login')) {
            $query->where('last_login', $request->last_login);
        }

        if ($request->has('created_at')) {
            $query->where('created_at', $request->created_at);
        }

        if ($request->has('updated_at')) {
            $query->where('updated_at', $request->updated_at);
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('user_id', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%')
                  ->orWhere('last_login', 'like', '%' . $request->search . '%')
                  ->orWhere('updated_at', 'like', '%' . $request->search . '%')
                  ->orWhere('created_at', 'like', '%' . $request->search . '%');
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