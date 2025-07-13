<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JadwalPelajaran;

class JadwalPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kelas_id'    => 1,
                'mapel_id'    => 1, // Matematika
                'hari'        => 'Senin',
                'jam_mulai'   => '08:00:00',
                'jam_selesai' => '09:30:00'
            ],
            [
                'kelas_id'    => 1,
                'mapel_id'    => 2, // Bahasa Indonesia
                'hari'        => 'Senin',
                'jam_mulai'   => '09:45:00',
                'jam_selesai' => '11:15:00'
            ],
            [
                'kelas_id'    => 2,
                'mapel_id'    => 3, // IPA
                'hari'        => 'Selasa',
                'jam_mulai'   => '08:00:00',
                'jam_selesai' => '09:30:00'
            ],
            [
                'kelas_id'    => 3,
                'mapel_id'    => 4, // IPS
                'hari'        => 'Rabu',
                'jam_mulai'   => '10:00:00',
                'jam_selesai' => '11:30:00'
            ],
        ];

        foreach ($data as $jadwal) {
            JadwalPelajaran::create($jadwal);
        }
    }
}
