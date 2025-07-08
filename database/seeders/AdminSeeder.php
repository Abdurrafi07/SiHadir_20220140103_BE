<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // unik cek
            [
                'name'      => 'admin',
                'email'     => 'admin@example.com',
                'password'  => Hash::make('admin123'), // ganti kalau mau
                'role_id'   => 1, // pastikan ini ID role admin
                'id_kelas'  => null, // admin tidak perlu kelas
            ]
        );
    }
}
