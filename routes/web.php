<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\HitungStokController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TransaksiController;
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
    // Kelola Barang
    Route::group(['middleware' => ['can:kelola barang']], function () {
        Route::get('kelola/barang', [BarangController::class, 'index'])->name('index.barang');
        Route::get('kelola/barang/tambah', [BarangController::class, 'tambahindex'])->name('tambah.index.barang');
        Route::post('kelola/barang/tambah/post', [BarangController::class, 'tambah'])->name('tambah.post.barang');
        Route::get('kelola/barang/edit/{id}', [BarangController::class, 'editindex'])->name('edit.index.barang');
        Route::post('kelola/barang/edit/{id}/post', [BarangController::class, 'edit'])->name('edit.post.barang');
        Route::get('kelola/barang/hapus/{id}', [BarangController::class, 'hapus'])->name('hapus.barang');
    });
    // Kelola Transaksi
    Route::group(['middleware' => ['can:kelola transaksi']], function () {
        Route::get('kelola/transaksi', [TransaksiController::class, 'index'])->name('index.transaksi');
        Route::get('kelola/transaksi/lihat/{id}', [TransaksiController::class, 'lihat_index'])->name('lihat.index.transaksi');
        Route::get('kelola/transaksi/lihat/cari/{id}', [TransaksiController::class, 'lihat_cari'])->name('lihat.cari.transaksi');
        Route::post('kelola/transaksi/lihat/cari/detail_vaksin/{id}/{tanggal}', [TransaksiController::class, 'post_detail_vaksin'])->name('lihat.cari.detail_vaksin.transaksi');
        Route::post('kelola/transaksi/lihat/cari/tambah_transaksi/{id}', [TransaksiController::class, 'tambah_transaksi'])->name('lihat.tambah.transaksi');
        Route::post('kelola/transaksi/lihat/cari/edit_transaksi', [TransaksiController::class, 'edit_transaksi'])->name('lihat.edit.transaksi');
        Route::post('kelola/transaksi/lihat/cari/tambah_transaksi_kabupaten/{id}', [TransaksiController::class, 'tambah_transaksi_kabupaten'])->name('lihat.tambah.transaksi.kabupaten');
        Route::post('kelola/transaksi/lihat/cari/edit_transaksi_kabupaten', [TransaksiController::class, 'edit_transaksi_kabupaten'])->name('lihat.edit.transaksi.kabupaten');
        Route::get('get-transaksi', [TransaksiController::class, 'get_transaksi'])->name('lihat.get_transaksi');
        Route::get('get-transaksi-kabupaten', [TransaksiController::class, 'get_transaksi_kabupaten'])->name('lihat.get_transaksi_kabupaten');
        Route::post('hitung-stok/{id}', [TransaksiController::class, 'hitung_stok'])->name('lihat.hitung_stok');
        Route::get('hapus-transaksi/{vaksin_id}/{id}', [TransaksiController::class, 'hapus'])->name('hapus.transaksi');
        Route::get('hapus-transaksi-kabupaten/{id}', [TransaksiController::class, 'hapus_kabupaten'])->name('hapus.transaksi.kabupaten');
    });
    Route::get('hitung-stok', [HitungStokController::class, 'index'])->name('index.hitung_stok');
    Route::post('hitung-stok-semua', [HitungStokController::class, 'hitung_stok_total'])->name('hitung_stok.semua');
    Route::post('hitung-stok-penerimaan', [HitungStokController::class, 'hitung_stok_penerimaan'])->name('hitung_stok.penerimaan');
    Route::post('hitung-stok-pengeluaran', [HitungStokController::class, 'hitung_stok_pengeluaran'])->name('hitung_stok.pengeluaran');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('kelola/transaksi/lihat/post', [HomeController::class, 'transaksi'])->name('lihat.index.transaksi.post');
});
