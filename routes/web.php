<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
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
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});
