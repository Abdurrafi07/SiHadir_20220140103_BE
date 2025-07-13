<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * Tampilkan semua data absensi.
     */
    public function index()
    {
        $absensi = Absensi::with(['siswa', 'jadwal'])->latest()->get();
        return response()->json($absensi);
    }

    /**
     * Simpan data presensi baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jadwal_id' => 'required|exists:jadwal_pelajaran,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alfa',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'alamat' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('presensi_foto', 'public');
        }

        $absensi = Absensi::create($validated);

        return response()->json([
            'message' => 'Data presensi berhasil disimpan.',
            'data' => $absensi,
        ], 201);
    }

    /**
     * Tampilkan detail absensi tertentu.
     */
    public function show($id)
    {
        $absensi = Absensi::with(['siswa', 'jadwal'])->findOrFail($id);
        return response()->json($absensi);
    }

    /**
     * Update data absensi.
     */
    public function update(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);

        $validated = $request->validate([
            'status' => 'in:hadir,izin,sakit,alfa',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'alamat' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('presensi_foto', 'public');
        }

        $absensi->update($validated);

        return response()->json([
            'message' => 'Data presensi berhasil diperbarui.',
            'data' => $absensi,
        ]);
    }

    /**
     * Hapus data absensi.
     */
    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return response()->json([
            'message' => 'Data presensi berhasil dihapus.',
        ]);
    }

    /**
     * Simpan presensi massal untuk semua siswa dalam satu jadwal.
     */
    public function storeMassal(Request $request)
    {
        $validated = $request->validate([
            'jadwal_id' => 'required|exists:jadwal_pelajaran,id',
            'tanggal' => 'required|date',
            'presensi' => 'required|array',
            'presensi.*.siswa_id' => 'required|exists:siswa,id',
            'presensi.*.status' => 'required|in:hadir,izin,sakit,alfa',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'alamat' => 'nullable|string|max:255',
        ]);

        // 🖼️ Simpan 1 foto jika ada
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('presensi_foto', 'public');
        }

        $data = [];
        foreach ($validated['presensi'] as $item) {
            $data[] = [
                'jadwal_id' => $validated['jadwal_id'],
                'siswa_id' => $item['siswa_id'],
                'tanggal' => $validated['tanggal'],
                'status' => $item['status'],
                'foto' => $validated['foto'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'alamat' => $validated['alamat'] ?? null, 
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Cek yang sudah absen
        $existing = Absensi::where('jadwal_id', $validated['jadwal_id'])
            ->where('tanggal', $validated['tanggal'])
            ->pluck('siswa_id')
            ->toArray();

        // Filter agar tidak dobel
        $data = array_filter($data, function ($item) use ($existing) {
            return !in_array($item['siswa_id'], $existing);
        });

        if (count($data) === 0) {
            return response()->json([
                'message' => 'Semua siswa sudah absen hari ini.',
                'inserted' => 0,
            ], 200);
        }

        Absensi::insert($data);

        return response()->json([
            'message' => 'Presensi massal berhasil disimpan.',
            'inserted' => count($data),
        ], 201);
    }
}
