<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\Siswa;
use Illuminate\Http\Request;

class JadwalGuruController extends Controller
{
    public function index()
    {
        $guru = auth()->user();

        if (!$guru->id_kelas) {
            return response()->json([
                'message' => 'Guru tidak terdaftar dalam kelas manapun.',
                'status_code' => 403
            ]);
        }

        // Ambil semua jadwal berdasarkan kelas guru
        $jadwal = JadwalPelajaran::with(['kelas', 'mapel'])
            ->where('kelas_id', $guru->id_kelas)
            ->get();

        // Ambil semua siswa berdasarkan kelas guru
        $siswa = Siswa::where('kelas_id', $guru->id_kelas)->get();

        return response()->json([
            'message' => 'Data jadwal dan siswa berhasil diambil',
            'status_code' => 200,
            'jadwal' => $jadwal,
            'siswa' => $siswa,
        ]);
    }
}
