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
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('username', 'like', '%' . $request->search . '%');
            });
        }
        
        $loginRecords = $query->get();
        
        if ($loginRecords->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login records not found'
            ], 404);
        }
        
        return response()->json($loginRecords);
    }
    
    public function show($id)
    {
        $login = Login::find($id);
        
        if (!$login) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login record not found'
            ], 404);
        }
        
        return response()->json($login);
    }
}