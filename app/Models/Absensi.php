<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id', 'guru_id', 'mapel_id', 'kelas_id', 'tanggal',
        'jam_mulai', 'jam_selesai', 'status', 'foto'
    ];

    public function siswa() {
        return $this->belongsTo(Siswa::class);
    }

    public function guru() {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function mapel() {
        return $this->belongsTo(Mapel::class);
    }

    public function kelas() {
        return $this->belongsTo(Kelas::class);
    }
}
