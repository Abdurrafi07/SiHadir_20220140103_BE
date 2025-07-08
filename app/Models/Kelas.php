<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas'; // optional, tapi aman ditulis

    protected $fillable = ['nama_kelas'];

    /**
     * Relasi ke user (satu kelas banyak user)
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_kelas');
    }

    public function mapel()
    {
        return $this->belongsToMany(Mapel::class, 'kelas_mapel', 'kelas_id', 'mapel_id');
    }

}
