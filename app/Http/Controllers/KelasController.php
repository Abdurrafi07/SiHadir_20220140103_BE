<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    // 🔍 Ambil semua data kelas
    public function index()
    {
        try {
            $kelas = Kelas::with('mapel')->get();

            if ($kelas->isEmpty()) {
                return response()->json([
                    'message' => 'Tidak ada data kelas yang ditemukan',
                    'status_code' => 404,
                    'data' => []
                ]);
            }

            $data = $kelas->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_kelas' => $item->nama_kelas,
                    'mapel' => $item->mapel->pluck('nama_mapel'),
                ];
            });

            return response()->json([
                'message' => 'Data kelas berhasil diambil',
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

    // 🆕 Tambah kelas
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kelas' => 'required|string|max:255|unique:kelas',
            'mapel_ids' => 'array',
            'mapel_ids.*' => 'exists:mapel,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'status_code' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kelas = Kelas::create([
                'nama_kelas' => $request->nama_kelas
            ]);

            if ($request->has('mapel_ids')) {
                $kelas->mapel()->sync($request->mapel_ids);
            }

            return response()->json([
                'message' => 'Data kelas berhasil ditambahkan',
                'status_code' => 201,
                'data' => $kelas->load('mapel')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan kelas: ' . $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ]);
        }
    }

    // 🔍 Ambil detail kelas
    public function show($id)
    {
        try {
            $kelas = Kelas::with('mapel')->find($id);

            if (!$kelas) {
                return response()->json([
                    'message' => 'Data kelas tidak ditemukan',
                    'status_code' => 404,
                    'data' => null
                ]);
            }

            return response()->json([
                'message' => 'Data kelas ditemukan',
                'status_code' => 200,
                'data' => [
                    'id' => $kelas->id,
                    'nama_kelas' => $kelas->nama_kelas,
                    'mapel' => $kelas->mapel->pluck('nama_mapel')
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

    // ✏️ Update kelas
    public function update(Request $request, $id)
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json([
                'message' => 'Data kelas tidak ditemukan',
                'status_code' => 404,
                'data' => null
            ]);
        }

        $validator = Validator::make($request->all(), [
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $id,
            'mapel_ids' => 'array',
            'mapel_ids.*' => 'exists:mapel,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'status_code' => 422,
                'errors' => $validator->errors()
            ]);
        }

        try {
            $kelas->nama_kelas = $request->nama_kelas;
            $kelas->save();

            if ($request->has('mapel_ids')) {
                $kelas->mapel()->sync($request->mapel_ids);
            }

            return response()->json([
                'message' => 'Data kelas berhasil diperbarui',
                'status_code' => 200,
                'data' => $kelas->load('mapel')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui kelas: ' . $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ]);
        }
    }

    // 🗑️ Hapus kelas
    public function destroy($id)
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json([
                'message' => 'Data kelas tidak ditemukan',
                'status_code' => 404,
                'data' => null
            ]);
        }

        try {
            $kelas->mapel()->detach();
            $kelas->delete();

            return response()->json([
                'message' => 'Data kelas berhasil dihapus',
                'status_code' => 200,
                'data' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus kelas: ' . $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ]);
        }
    }
}
