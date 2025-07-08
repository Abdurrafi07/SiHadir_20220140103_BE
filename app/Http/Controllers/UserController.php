<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // 🔍 Ambil semua guru
    public function index()
    {
        $guru = User::with('kelas')
            ->where('role_id', 2)
            ->get();

        if ($guru->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada data guru ditemukan',
                'status_code' => 404,
                'data' => []
            ]);
        }

        $data = $guru->map(function ($g) {
            return [
                'id' => $g->id,
                'nama' => $g->name,
                'email' => $g->email,
                'kelas_diampu' => optional($g->kelas)->nama_kelas
            ];
        });

        return response()->json([
            'message' => 'Data guru berhasil diambil',
            'status_code' => 200,
            'data' => $data
        ]);
    }

    // 🆕 Tambah guru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'kelas_id' => 'nullable|exists:kelas,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'status_code' => 422,
                'errors' => $validator->errors()
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2, // 2 adalah ID untuk guru
            'id_kelas' => $request->kelas_id
        ]);

        return response()->json([
            'message' => 'Guru berhasil ditambahkan',
            'status_code' => 201,
            'data' => $user->load('kelas')
        ]);
    }

    // 🔍 Detail guru
    public function show($id)
    {
        $user = User::with('kelas')
            ->where('role_id', 2)
            ->find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Data guru tidak ditemukan',
                'status_code' => 404,
                'data' => null
            ]);
        }

        return response()->json([
            'message' => 'Data guru ditemukan',
            'status_code' => 200,
            'data' => [
                'id' => $user->id,
                'nama' => $user->name,
                'email' => $user->email,
                'kelas_diampu' => optional($user->kelas)->nama_kelas
            ]
        ]);
    }

    // ✏️ Update guru
    public function update(Request $request, $id)
    {
        $user = User::where('role_id', 2)->find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Data guru tidak ditemukan',
                'status_code' => 404,
                'data' => null
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'kelas_id' => 'nullable|exists:kelas,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'status_code' => 422,
                'errors' => $validator->errors()
            ]);
        }

        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'id_kelas' => $request->kelas_id ?? $user->id_kelas
        ]);

        return response()->json([
            'message' => 'Data guru berhasil diperbarui',
            'status_code' => 200,
            'data' => $user->load('kelas')
        ]);
    }

    // 🗑️ Hapus guru
    public function destroy($id)
    {
        $user = User::where('role_id', 2)->find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Data guru tidak ditemukan',
                'status_code' => 404
            ]);
        }

        $user->delete();

        return response()->json([
            'message' => 'Data guru berhasil dihapus',
            'status_code' => 200
        ]);
    }
}
