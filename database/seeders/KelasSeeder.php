<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelasList = ['Kelas 1A', 'Kelas 2B', 'Kelas 3C'];

        foreach ($kelasList as $nama) {
            Kelas::firstOrCreate(['nama_kelas' => $nama]);
        }

        echo "✅ KelasSeeder selesai\n";
    }
}
