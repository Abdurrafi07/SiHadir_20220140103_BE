<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\JadwalGuruController;
use App\Http\Controllers\JadwalPelajaranController;
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
    Route::get('admin/jadwal', [JadwalPelajaranController::class, 'index']);
    Route::post('admin/jadwal', [JadwalPelajaranController::class, 'store']);
    Route::get('admin/jadwal/{id}', [JadwalPelajaranController::class, 'show']);
    Route::put('admin/jadwal/{id}', [JadwalPelajaranController::class, 'update']);
    Route::delete('admin/jadwal/{id}', [JadwalPelajaranController::class, 'destroy']);
    
});

Route::middleware(['auth:api', 'role:guru'])->group(function () {
    Route::get('absensi', [AbsensiController::class, 'index']);
    Route::post('absensi', [AbsensiController::class, 'store']);
    Route::get('absensi/{id}', [AbsensiController::class, 'show']);
    Route::put('absensi/{id}', [AbsensiController::class, 'update']);
    Route::delete('/absensi/{id}', [AbsensiController::class, 'destroy']);
    Route::post('absensi/massal', [AbsensiController::class, 'storeMassal']);
    Route::get('jadwal/guru', [JadwalGuruController::class, 'index']);
});