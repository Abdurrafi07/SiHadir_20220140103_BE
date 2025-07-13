<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mapel;

class MapelSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_mapel' => 'Matematika',
                'kelas_ids' => [1, 2, 3]
            ],
            [
                'nama_mapel' => 'Bahasa Indonesia',
                'kelas_ids' => [1, 2]
            ],
            [
                'nama_mapel' => 'IPA',
                'kelas_ids' => [2, 3]
            ],
            [
                'nama_mapel' => 'IPS',
                'kelas_ids' => [3]
            ],
            [
                'nama_mapel' => 'Bahasa Sunda',
                'kelas_ids' => [1, 3]
            ]
        ];

        foreach ($data as $mapelData) {
            $mapel = Mapel::create([
                'nama_mapel' => $mapelData['nama_mapel']
            ]);

            $mapel->kelas()->sync($mapelData['kelas_ids']); // hubungkan ke kelas
        }
    }
}
