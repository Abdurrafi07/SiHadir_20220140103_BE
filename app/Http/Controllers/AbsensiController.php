<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:siswa,id',
            'guru_id' => 'required|exists:users,id',
            'mapel_id' => 'required|exists:mapel,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
            'status' => 'required|in:hadir,izin,sakit,alfa',
            'foto' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'status_code' => 422,
                'errors' => $validator->errors()
            ]);
        }

        // simpan foto jika ada
        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('absensi_foto', 'public');
        }

        $absensi = Absensi::create(array_merge(
            $validator->validated(),
            ['foto' => $path]
        ));

        return response()->json([
            'message' => 'Absensi berhasil dicatat',
            'status_code' => 201,
            'data' => $absensi
        ]);
    }

}
