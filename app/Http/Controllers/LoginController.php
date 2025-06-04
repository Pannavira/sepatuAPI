<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Login;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LoginController extends Controller
{
    /**
     * Display a listing of login records
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Login::query();
            
            // Filter by specific fields
            if ($request->has('id')) {
                $query->where('id', $request->id);
            }

            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            
            if ($request->has('username')) {
                $query->where('username', 'like', '%' . $request->username . '%');
            }

            if ($request->has('last_login')) {
                $query->whereDate('last_login', $request->last_login);
            }

            if ($request->has('created_at')) {
                $query->whereDate('created_at', $request->created_at);
            }

            if ($request->has('updated_at')) {
                $query->whereDate('updated_at', $request->updated_at);
            }
            
            // Global search
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('id', 'like', '%' . $searchTerm . '%')
                      ->orWhere('user_id', 'like', '%' . $searchTerm . '%')
                      ->orWhere('username', 'like', '%' . $searchTerm . '%')
                      ->orWhere('last_login', 'like', '%' . $searchTerm . '%');
                });
            }
            
            // Pagination
            $perPage = $request->get('per_page', 10);
            $data = $query->paginate($perPage);

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available users that don't have login yet
     *
     * @return JsonResponse
     */
    public function getAvailableUsers(): JsonResponse
    {
        try {
            $usedUserIds = Login::pluck('user_id')->toArray();
            $availableUsers = User::whereNotIn('id', $usedUserIds)->get(['id', 'name', 'email']);

            return response()->json([
                'status' => 'success',
                'message' => 'Data user tersedia berhasil diambil',
                'data' => $availableUsers
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created login record
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id|unique:login,user_id',
                'username' => 'required|string|max:255|unique:login,username',
                'password' => 'required|string|min:6',
            ], [
                'user_id.required' => 'User ID wajib diisi',
                'user_id.exists' => 'User ID tidak ditemukan di tabel users',
                'user_id.unique' => 'User ID sudah digunakan',
                'username.required' => 'Username wajib diisi',
                'username.unique' => 'Username sudah digunakan',
                'password.required' => 'Password wajib diisi',
                'password.min' => 'Password minimal 6 karakter',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $login = Login::create([
                'user_id' => $request->user_id,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'last_login' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data login berhasil dibuat',
                'data' => $login
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified login record
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $login = Login::find($id);

            if (!$login) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data login tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data login berhasil diambil',
                'data' => $login
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified login record
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $login = Login::find($id);

            if (!$login) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data login tidak ditemukan'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'user_id' => 'sometimes|integer|exists:users,id|unique:login,user_id,' . $id,
                'username' => 'sometimes|string|max:255|unique:login,username,' . $id,
                'password' => 'sometimes|string|min:6',
            ], [
                'user_id.exists' => 'User ID tidak ditemukan di tabel users',
                'user_id.unique' => 'User ID sudah digunakan',
                'username.unique' => 'Username sudah digunakan',
                'password.min' => 'Password minimal 6 karakter',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update fields if provided
            if ($request->has('user_id')) {
                $login->user_id = $request->user_id;
            }
            
            if ($request->has('username')) {
                $login->username = $request->username;
            }
            
            if ($request->has('password')) {
                $login->password = Hash::make($request->password);
            }

            $login->updated_at = now();
            $login->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Data login berhasil diperbarui',
                'data' => $login
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified login record
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $login = Login::find($id);

            if (!$login) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data login tidak ditemukan'
                ], 404);
            }

            $login->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data login berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update last login timestamp
     *
     * @param int $id
     * @return JsonResponse
     */
    public function updateLastLogin($id): JsonResponse
    {
        try {
            $login = Login::find($id);

            if (!$login) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data login tidak ditemukan'
                ], 404);
            }

            $login->last_login = now();
            $login->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Last login berhasil diperbarui',
                'data' => $login
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Authenticate user login
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function authenticate(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
                'password' => 'required|string',
            ], [
                'username.required' => 'Username wajib diisi',
                'password.required' => 'Password wajib diisi',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $login = Login::where('username', $request->username)->first();

            if (!$login || !Hash::check($request->password, $login->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Username atau password salah'
                ], 401);
            }

            // Update last login
            $login->last_login = now();
            $login->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil',
                'data' => [
                    'id' => $login->id,
                    'user_id' => $login->user_id,
                    'username' => $login->username,
                    'last_login' => $login->last_login,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}