<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    // READ - Get all users with optional filters
    public function index(Request $request)
    {
        $query = Users::query();
        
        // Filter by specific fields
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        
        if ($request->has('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        
        if ($request->has('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }
        
        if ($request->has('address')) {
            $query->where('address', 'like', '%' . $request->address . '%');
        }
        
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }
        
        // Global search filter
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('role', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }
        
        $get = $query->get();

        if ($get->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'value tidak ditemukan'
            ], 404);
        }

        return response()->json($get);
    }

    // CREATE - Store a new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'role' => 'required|in:user,admin'
        ]);

        $user = Users::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role
        ]);

        // Remove password from response for security
        $user->makeHidden(['password']);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    // READ - Show a single user by ID
    public function show($id)
    {
        $user = Users::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        // Hide password from response
        $user->makeHidden(['password']);

        return response()->json($user);
    }

    // UPDATE - Update an existing user
    public function update(Request $request, $id)
    {
        $user = Users::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id . '|max:255',
            'password' => 'sometimes|required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'role' => 'sometimes|required|in:user,admin'
        ]);

        $updateData = $request->only(['name', 'email', 'phone', 'address', 'role']);

        // Hash password if provided
        if ($request->has('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // Hide password from response
        $user->makeHidden(['password']);

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    // DELETE - Delete a user
    public function destroy($id)
    {
        $user = Users::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ]);
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = Users::where('email', $request->email)->first();

    if (!$user || !\Hash::check($request->password, $user->password)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Email atau password salah'
        ], 401);
    }

    if ($user->role !== 'admin') {
        return response()->json([
            'status' => 'error',
            'message' => 'Akses hanya untuk admin'
        ], 403);
    }

    return response()->json([
        'status' => 'success',
        'user' => $user->makeHidden(['password'])
    ]);

    
}

}