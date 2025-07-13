<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'siswa_id',
        'jadwal_id',
        'tanggal',
        'status',
        'foto',
        'latitude',
        'longitude',
    ];

    /**
     * Relasi ke model Siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Relasi ke model JadwalPelajaran
     */
    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id');
    }

    // Opsional: akses langsung ke kelas dari siswa
    public function kelas()
    {
        return $this->siswa?->kelas(); // Asumsikan Siswa punya relasi ke Kelas
    }
}
