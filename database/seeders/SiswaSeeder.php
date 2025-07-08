<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Ahmad Prasetyo',
                'nisn' => '1234567890',
                'jenis_kelamin' => 'laki-laki',
                'tanggal_lahir' => '2010-05-01',
                'alamat' => 'Jl. Merdeka No.1',
                'kelas_id' => 1
            ],
            [
                'nama' => 'Siti Aisyah',
                'nisn' => '1234567891',
                'jenis_kelamin' => 'perempuan',
                'tanggal_lahir' => '2010-06-12',
                'alamat' => 'Jl. Mawar No.3',
                'kelas_id' => 2
            ],
            [
                'nama' => 'Budi Santoso',
                'nisn' => '1234567892',
                'jenis_kelamin' => 'laki-laki',
                'tanggal_lahir' => '2011-01-20',
                'alamat' => 'Jl. Anggrek No.5',
                'kelas_id' => 3
            ],
        ];

        foreach ($data as $siswa) {
            Siswa::create($siswa);
        }
    }
}
