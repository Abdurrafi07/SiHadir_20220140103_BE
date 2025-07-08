<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(RegisterRequest $request)
{
    try {
        $user = new User;
        $user->name     = $request->username;
        $user->email    = $request->email;
        $user->password = Hash::make($request->password);
        $user->role_id  = 2; // otomatis sebagai guru
        $user->id_kelas = null;

        $user->save();

        return response()->json([
            'status_code' => 201,
            'message' => 'User created successfully',
            'data'    => $user,
        ], 201);
    } catch (Exception $e) {
        return response()->json([
            'status_code' => 500,
            'message' => $e->getMessage(),
            'data' => null
        ], 500);
    }
}

    /**
     * Login user
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Email atau password salah',
                'status_code' => 401,
                'data' => null
            ], 401);
        }

        try {
            $user = Auth::guard('api')->user();

            // Admin bebas login, selain admin wajib punya kelas
            if ($user->role_id != 1 && !$user->id_kelas) {
                return response()->json([
                    'message' => 'Guru harus terdaftar dalam kelas.',
                    'status_code' => 403,
                    'data' => null
                ], 403);
            }

            $formatedUser = [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role->name ?? null,
                'kelas' => $user->kelas->nama_kelas ?? null,
                'token' => $token
            ];

            return response()->json([
                'message' => 'Login berhasil',
                'status_code' => 200,
                'data' => $formatedUser
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ], 500);
        }
    }

    /**
     * Ambil data user yang sedang login
     */
    public function me()
    {
        try {
            $user = Auth::guard('api')->user();

            $formatedUser = [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role->name ?? null,
                'kelas' => $user->kelas->nama_kelas ?? null,
            ];

            return response()->json([
                'message' => 'User ditemukan',
                'status_code' => 200,
                'data' => $formatedUser
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ], 500);
        }
    }

    /**
     * Logout user
     */
    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json([
            'message' => 'Logout berhasil',
            'status_code' => 200,
            'data' => null
        ]);
    }
}
