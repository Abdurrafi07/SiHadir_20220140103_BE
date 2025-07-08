<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiswaTable extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nisn')->unique();
            $table->string('jenis_kelamin');
            $table->date('tanggal_lahir')->nullable();
            $table->string('alamat')->nullable();
            $table->unsignedBigInteger('kelas_id')->nullable(); // nullable jika belum punya kelas
            $table->timestamps();

            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
}
