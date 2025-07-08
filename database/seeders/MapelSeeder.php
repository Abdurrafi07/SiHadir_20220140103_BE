<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mapel;

class MapelSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [ 'nama_mapel' => 'Matematika' ],
            [ 'nama_mapel' => 'Bahasa Indonesia' ],
            [ 'nama_mapel' => 'IPA' ],
            [ 'nama_mapel' => 'IPS' ],
            [ 'nama_mapel' => 'Bahasa Sunda' ]
        ];

        foreach ($data as $mapel) {
            Mapel::create($mapel);
        }
    }
}
