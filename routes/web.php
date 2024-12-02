<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\GalleryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\Api\BookApiController;
use App\Http\Controllers\ReviewController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/apiapi', function () {
    return view('apiapi');
});

Route::post('/books', [BookApiController::class, 'store']);

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

Route::delete('/gallery/{id}', [BukuController::class, 'destroyGallery'])->name('gallery.destroy');

Route::middleware(['auth'])->group(function () {
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/tag/{tag}', [ReviewController::class, 'byTag'])->name('reviews.by-tag');
    Route::get('/reviews/reviewer/{user}', [ReviewController::class, 'byReviewer'])->name('reviews.by-reviewer');
    Route::middleware(['can:manage-reviews'])->group(function () {
        Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    });
    Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
});


/* About Page */
Route::get('/about', function () {
    return view('about');
})->name('about');

