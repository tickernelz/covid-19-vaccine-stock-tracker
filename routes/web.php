<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth
Route::get('/', [AuthController::class, 'formlogin'])->name('index');
Route::get('login', [AuthController::class, 'formlogin'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('login', [AuthController::class, 'login'])->name('post-login');

// Route Akses
Route::group(['middleware' => 'auth'], function () {
    // Kelola User
    Route::group(['middleware' => ['can:kelola user']], function () {
        Route::get('kelola/users', [UserController::class, 'index'])->name('index.user');
        Route::get('kelola/users/tambah', [UserController::class, 'tambahindex'])->name('tambah.index.user');
        Route::post('kelola/users/tambah/post', [UserController::class, 'tambah'])->name('tambah.post.user');
        Route::get('kelola/users/edit/{id}', [UserController::class, 'editindex'])->name('edit.index.user');
        Route::post('kelola/users/edit/{id}/post', [UserController::class, 'edit'])->name('edit.post.user');
        Route::get('kelola/users/hapus/{id}', [UserController::class, 'hapus'])->name('hapus.user');
    });
    // Kelola Surat
    Route::group(['middleware' => ['can:kelola surat']], function () {
        // Kelola Surat Masuk
        Route::get('kelola/surat/masuk', [SuratMasukController::class, 'index'])->name('index.surat.masuk');
        Route::get('kelola/surat/masuk/tambah', [SuratMasukController::class, 'tambahindex'])->name('tambah.index.surat.masuk');
        Route::post('kelola/surat/masuk/tambah/post', [SuratMasukController::class, 'tambah'])->name('tambah.post.surat.masuk');
        Route::get('kelola/surat/masuk/edit/{id}', [SuratMasukController::class, 'editindex'])->name('edit.index.surat.masuk');
        Route::post('kelola/surat/masuk/edit/{id}/post', [SuratMasukController::class, 'edit'])->name('edit.post.surat.masuk');
        Route::get('kelola/surat/masuk/lihat/{id}', [SuratMasukController::class, 'lihat'])->name('lihat.surat.masuk');
        Route::get('kelola/surat/masuk/hapus/{id}', [SuratMasukController::class, 'hapus'])->name('hapus.surat.masuk');
        Route::get('kelola/surat/masuk/hapus-berkas/{id}', [SuratMasukController::class, 'hapus_berkas'])->name('hapus.berkas.surat.masuk');
        // Kelola Surat Keluar
        Route::get('kelola/surat/keluar', [SuratKeluarController::class, 'index'])->name('index.surat.keluar');
        Route::get('kelola/surat/keluar/tambah', [SuratKeluarController::class, 'tambahindex'])->name('tambah.index.surat.keluar');
        Route::post('kelola/surat/keluar/tambah/post', [SuratKeluarController::class, 'tambah'])->name('tambah.post.surat.keluar');
        Route::get('kelola/surat/keluar/edit/{id}', [SuratKeluarController::class, 'editindex'])->name('edit.index.surat.keluar');
        Route::post('kelola/surat/keluar/edit/{id}/post', [SuratKeluarController::class, 'edit'])->name('edit.post.surat.keluar');
        Route::get('kelola/surat/keluar/lihat/{id}', [SuratKeluarController::class, 'lihat'])->name('lihat.surat.keluar');
        Route::get('kelola/surat/keluar/hapus/{id}', [SuratKeluarController::class, 'hapus'])->name('hapus.surat.keluar');
        Route::get('kelola/surat/keluar/hapus-berkas/{id}', [SuratKeluarController::class, 'hapus_berkas'])->name('hapus.berkas.surat.keluar');
    });
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});
