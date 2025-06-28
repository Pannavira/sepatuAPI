<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Cari user admin dengan email
        $user = Users::where('email', $request->email)
                     ->where('role', 'admin')
                     ->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User admin tidak ditemukan.'
            ], 404);
        }

        // Cek password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password salah.'
            ], 401);
        }

        // Hapus password dari response
        $user->makeHidden(['password']);

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'data' => $user
        ]);
    }
}
