<?php

use App\Http\Controllers\AnakController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndukController;
use App\Http\Controllers\PostingJualController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;

use App\Http\Controllers\MapelController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;

use App\Models\Induk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api', 'role:admin'])->group(function () {

    // 📚 Route untuk Mapel
    Route::get('admin/mapel', [MapelController::class, 'index']);
    Route::post('admin/mapel', [MapelController::class, 'store']);
    Route::get('admin/mapel/{id}', [MapelController::class, 'show']);
    Route::put('admin/mapel/{id}', [MapelController::class, 'update']);
    Route::delete('admin/mapel/{id}', [MapelController::class, 'destroy']);

    // 🏫 Route untuk Kelas
    Route::get('admin/kelas', [KelasController::class, 'index']);
    Route::post('admin/kelas', [KelasController::class, 'store']);
    Route::get('admin/kelas/{id}', [KelasController::class, 'show']);
    Route::put('admin/kelas/{id}', [KelasController::class, 'update']);
    Route::delete('admin/kelas/{id}', [KelasController::class, 'destroy']);

    // 📘 Route Siswa
    Route::get('admin/siswa', [SiswaController::class, 'index']);
    Route::post('admin/siswa', [SiswaController::class, 'store']);
    Route::get('admin/siswa/{id}', [SiswaController::class, 'show']);
    Route::put('admin/siswa/{id}', [SiswaController::class, 'update']);
    Route::delete('admin/siswa/{id}', [SiswaController::class, 'destroy']);

    // Route Guru
    Route::get('admin/guru', [UserController::class, 'index']);
    Route::post('admin/guru', [UserController::class, 'store']);
    Route::get('admin/guru/{id}', [UserController::class, 'show']);
    Route::put('admin/guru/{id}', [UserController::class, 'update']);
    Route::delete('admin/guru/{id}', [UserController::class, 'destroy']);

    // Route Jadwal Pelajaran
    Route::get('admin/jadwal', [\App\Http\Controllers\JadwalPelajaranController::class, 'index']);
    Route::post('admin/jadwal', [\App\Http\Controllers\JadwalPelajaranController::class, 'store']);
    Route::get('admin/jadwal/{id}', [\App\Http\Controllers\JadwalPelajaranController::class, 'show']);
    Route::put('admin/jadwal/{id}', [\App\Http\Controllers\JadwalPelajaranController::class, 'update']);
    Route::delete('admin/jadwal/{id}', [\App\Http\Controllers\JadwalPelajaranController::class, 'destroy']);

});

Route::middleware(['auth:api', 'role:guru'])->group(function () {
    Route::post('guru/absensi', [AbsensiController::class, 'store']);
    Route::get('guru/absensi', [AbsensiController::class, 'index']); // nanti bisa pakai filter bulan/kelas
});