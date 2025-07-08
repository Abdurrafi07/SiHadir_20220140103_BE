<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MapelController extends Controller
{
    // 🔍 Ambil semua mapel
    public function index()
    {
        try {
            $mapel = Mapel::with('kelas')->get();

            if ($mapel->isEmpty()) {
                return response()->json([
                    'message' => 'Tidak ada data mapel yang ditemukan',
                    'status_code' => 404,
                    'data' => []
                ]);
            }

            $data = $mapel->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_mapel' => $item->nama_mapel,
                    'kelas' => $item->kelas->pluck('nama_kelas')
                ];
            });

            return response()->json([
                'message' => 'Data mapel berhasil diambil',
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

    // 🆕 Tambah mapel
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_mapel' => 'required|string|max:255|unique:mapel',
            'kelas_ids' => 'array',
            'kelas_ids.*' => 'exists:kelas,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'status_code' => 422,
                'errors' => $validator->errors()
            ]);
        }

        try {
            $mapel = Mapel::create([
                'nama_mapel' => $request->nama_mapel
            ]);

            if ($request->has('kelas_ids')) {
                $mapel->kelas()->sync($request->kelas_ids);
            }

            return response()->json([
                'message' => 'Mapel berhasil ditambahkan',
                'status_code' => 201,
                'data' => $mapel->load('kelas')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan mapel: ' . $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ]);
        }
    }

    // 🔍 Detail mapel
    public function show($id)
    {
        try {
            $mapel = Mapel::with('kelas')->find($id);

            if (!$mapel) {
                return response()->json([
                    'message' => 'Data mapel tidak ditemukan',
                    'status_code' => 404,
                    'data' => null
                ]);
            }

            return response()->json([
                'message' => 'Data mapel ditemukan',
                'status_code' => 200,
                'data' => [
                    'id' => $mapel->id,
                    'nama_mapel' => $mapel->nama_mapel,
                    'kelas' => $mapel->kelas->pluck('nama_kelas')
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

    // ✏️ Update mapel
    public function update(Request $request, $id)
    {
        $mapel = Mapel::find($id);

        if (!$mapel) {
            return response()->json([
                'message' => 'Data mapel tidak ditemukan',
                'status_code' => 404,
                'data' => null
            ]);
        }

        $validator = Validator::make($request->all(), [
            'nama_mapel' => 'required|string|max:255|unique:mapel,nama_mapel,' . $id,
            'kelas_ids' => 'array',
            'kelas_ids.*' => 'exists:kelas,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'status_code' => 422,
                'errors' => $validator->errors()
            ]);
        }

        try {
            $mapel->nama_mapel = $request->nama_mapel;
            $mapel->save();

            if ($request->has('kelas_ids')) {
                $mapel->kelas()->sync($request->kelas_ids);
            }

            return response()->json([
                'message' => 'Data mapel berhasil diperbarui',
                'status_code' => 200,
                'data' => $mapel->load('kelas')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui mapel: ' . $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ]);
        }
    }

    // 🗑️ Hapus mapel
    public function destroy($id)
    {
        $mapel = Mapel::find($id);

        if (!$mapel) {
            return response()->json([
                'message' => 'Data mapel tidak ditemukan',
                'status_code' => 404,
                'data' => null
            ]);
        }

        try {
            $mapel->kelas()->detach();
            $mapel->delete();

            return response()->json([
                'message' => 'Data mapel berhasil dihapus',
                'status_code' => 200,
                'data' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus mapel: ' . $e->getMessage(),
                'status_code' => 500,
                'data' => null
            ]);
        }
    }
}
