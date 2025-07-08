<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiswaController extends Controller
{
    // 🔍 Ambil semua siswa
    public function index(Request $request)
{
    try {
        $query = Siswa::with('kelas'); // pastikan relasi ini ada di model

        // ✅ Ini bagian penting: filtering jika kelas_id ada
if ($request->query('kelas_id')) {
    $query->where('kelas_id', $request->query('kelas_id'));
}


        $siswa = $query->get();

        if ($siswa->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada data siswa ditemukan',
                'status_code' => 404,
                'data' => []
            ]);
        }

        $data = $siswa->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'nisn' => $item->nisn,
                'jenis_kelamin' => $item->jenis_kelamin,
                'tanggal_lahir' => $item->tanggal_lahir,
                'alamat' => $item->alamat,
                'kelas' => optional($item->kelas)->nama_kelas
            ];
        });

        return response()->json([
            'message' => 'Data siswa berhasil diambil',
            'status_code' => 200,
            'data' => $data
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            'status_code' => 500,
            'data' => null
        ]);
    }
}



    // 🆕 Tambah siswa
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:siswa',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:255',
            'kelas_id' => 'nullable|exists:kelas,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'status_code' => 422,
                'errors' => $validator->errors()
            ]);
        }

        try {
            $siswa = Siswa::create($validator->validated());

            return response()->json([
                'message' => 'Siswa berhasil ditambahkan',
                'status_code' => 201,
                'data' => $siswa->load('kelas')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan siswa: ' . $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ]);
        }
    }

    // 🔍 Detail siswa
    public function show($id)
    {
        try {
            $siswa = Siswa::with('kelas')->find($id);

            if (!$siswa) {
                return response()->json([
                    'message' => 'Data siswa tidak ditemukan',
                    'status_code' => 404,
                    'data' => null
                ]);
            }

            return response()->json([
                'message' => 'Data siswa ditemukan',
                'status_code' => 200,
                'data' => [
                    'id' => $siswa->id,
                    'nama' => $siswa->nama,
                    'nisn' => $siswa->nisn,
                    'jenis_kelamin' => $siswa->jenis_kelamin,
                    'tanggal_lahir' => $siswa->tanggal_lahir,
                    'alamat' => $siswa->alamat,
                    'kelas' => optional($siswa->kelas)->nama_kelas
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ]);
        }
    }

    // ✏️ Update siswa
    public function update(Request $request, $id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'message' => 'Data siswa tidak ditemukan',
                'status_code' => 404,
                'data' => null
            ]);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:siswa,nisn,' . $id,
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:255',
            'kelas_id' => 'nullable|exists:kelas,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'status_code' => 422,
                'errors' => $validator->errors()
            ]);
        }

        try {
            $siswa->update($validator->validated());

            return response()->json([
                'message' => 'Data siswa berhasil diperbarui',
                'status_code' => 200,
                'data' => $siswa->load('kelas')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui siswa: ' . $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ]);
        }
    }

    // 🗑️ Hapus siswa
    public function destroy($id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'message' => 'Data siswa tidak ditemukan',
                'status_code' => 404,
                'data' => null
            ]);
        }

        try {
            $siswa->delete();

            return response()->json([
                'message' => 'Data siswa berhasil dihapus',
                'status_code' => 200,
                'data' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus siswa: ' . $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ]);
        }
    }
}
