<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\Siswa;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    /**
     * Menampilkan kelas yang diajar oleh guru login
     */
    public function kelasSaya(Request $request)
    {
        $user = auth()->user();

        if (!$user->kelas) {
            return response()->json([
                'message' => 'Guru belum terhubung ke kelas manapun',
                'status_code' => 404
            ]);
        }

        return response()->json([
            'message' => 'Kelas ditemukan',
            'status_code' => 200,
            'data' => $user->kelas
        ]);
    }

    /**
     * Menampilkan jadwal pelajaran berdasarkan kelas guru login
     */
    public function jadwalSaya(Request $request)
    {
        $user = auth()->user();

        $jadwal = JadwalPelajaran::with(['mapel', 'kelas'])
            ->where('kelas_id', $user->id_kelas)
            ->get();

        return response()->json([
            'message' => 'Jadwal ditemukan',
            'status_code' => 200,
            'data' => $jadwal
        ]);
    }

    /**
     * Menampilkan daftar siswa berdasarkan kelas
     */
    public function siswaByKelas($kelas_id)
    {
        $siswa = Siswa::where('kelas_id', $kelas_id)->get();

        return response()->json([
            'message' => 'Daftar siswa ditemukan',
            'status_code' => 200,
            'data' => $siswa
        ]);
    }
}