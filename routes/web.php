<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\GalleryController; // Tambahkan import ini
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(LoginRegisterController::class)->group(function() {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/logout', 'logout');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(BukuController::class)->group(function () {
    Route::get('/buku', 'index');
    
    /* Tambah Buku */
    Route::get('/buku/create', 'create')->name('buku.create');
    Route::post('/buku', 'store')->name('buku.store');
    
    /* Delete Data Buku */
    Route::delete('/buku/{id}', 'destroy')->name('buku.destroy');
    
    /* Edit Data Buku */
    Route::get('/buku/{id}/edit', 'edit')->name('buku.edit');
    Route::put('/buku/{id}', 'update')->name('buku.update');
    
    /* Cari Buku */
    Route::get('/buku/search', 'search')->name('buku.search');
});

/* Route untuk menghapus gambar galeri */
Route::delete('/gallery/{id}', [GalleryController::class, 'destroy'])->name('gallery.delete'); // Tambahkan route ini

/* About Page */
Route::get('/about', function () {
    return view('about');
})->name('about');

