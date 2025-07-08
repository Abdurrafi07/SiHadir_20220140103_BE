<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalPelajaranController extends Controller
{
    // 🔍 Semua jadwal
    public function index()
    {
        $jadwal = JadwalPelajaran::with('kelas', 'mapel')->get();

        return response()->json([
            'message' => 'Data jadwal berhasil diambil',
            'status_code' => 200,
            'data' => $jadwal
        ]);
    }

    // 🆕 Tambah jadwal
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapel,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'status_code' => 422,
                'errors' => $validator->errors()
            ]);
        }

        $jadwal = JadwalPelajaran::create($validator->validated());

        return response()->json([
            'message' => 'Jadwal berhasil ditambahkan',
            'status_code' => 201,
            'data' => $jadwal->load('kelas', 'mapel')
        ]);
    }

    // 🔍 Detail
    public function show($id)
    {
        $jadwal = JadwalPelajaran::with('kelas', 'mapel')->find($id);

        if (!$jadwal) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
                'status_code' => 404,
                'data' => null
            ]);
        }

        return response()->json([
            'message' => 'Data ditemukan',
            'status_code' => 200,
            'data' => $jadwal
        ]);
    }

    // ✏️ Update
    public function update(Request $request, $id)
    {
        $jadwal = JadwalPelajaran::find($id);

        if (!$jadwal) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
                'status_code' => 404
            ]);
        }

        $validator = Validator::make($request->all(), [
            'kelas_id' => 'sometimes|exists:kelas,id',
            'mapel_id' => 'sometimes|exists:mapel,id',
            'hari' => 'sometimes|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'sometimes|date_format:H:i',
            'jam_selesai' => 'sometimes|date_format:H:i|after:jam_mulai'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'status_code' => 422,
                'errors' => $validator->errors()
            ]);
        }

        $jadwal->update($validator->validated());

        return response()->json([
            'message' => 'Jadwal berhasil diperbarui',
            'status_code' => 200,
            'data' => $jadwal->load('kelas', 'mapel')
        ]);
    }

    // 🗑️ Hapus
    public function destroy($id)
    {
        $jadwal = JadwalPelajaran::find($id);

        if (!$jadwal) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
                'status_code' => 404
            ]);
        }

        $jadwal->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus',
            'status_code' => 200
        ]);
    }
}
