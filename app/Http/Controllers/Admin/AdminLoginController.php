<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $response = Http::post(url('/api/admin/login'), [
        'email' => $request->email,
        'password' => $request->password
    ]);

    if ($response->failed()) {
        return back()->withErrors($response->json('message') ?? 'Login gagal.');
    }

    $user = $response->json('user');
    Session::put('admin_user', $user);
    return redirect()->route('admin.dashboard');
}


    public function logout()
    {
        Session::forget('admin_user');
        return redirect()->route('admin.login');
    }
}

