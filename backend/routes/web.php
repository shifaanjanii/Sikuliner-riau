<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\KabupatenController;
use App\Http\Controllers\Admin\KulinerKhasController;
use App\Http\Controllers\Admin\TokoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kuliner', [CatalogController::class, 'index'])->name('catalog');
Route::get('/kuliner/{id}', [CatalogController::class, 'show'])->name('food.show');
Route::get('/toko', [ShopController::class, 'index'])->name('shops.browse');
Route::get('/toko/{id}', [ShopController::class, 'show'])->name('shops.show');
Route::get('/tentang', [AboutController::class, 'index'])->name('about');

// Guest authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'storeLogin'])->middleware('throttle:30,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'storeRegistration'])->middleware('throttle:10,1')->name('register.store');
});

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::view('/akun', 'account')->name('account');
});

// Protected administrator routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD Kabupaten / Kota
    Route::get('/wilayah', [KabupatenController::class, 'index'])->name('regions.index');
    Route::get('/wilayah/tambah', [KabupatenController::class, 'create'])->name('regions.create');
    Route::post('/wilayah', [KabupatenController::class, 'store'])->name('regions.store');
    Route::get('/wilayah/{id}/edit', [KabupatenController::class, 'edit'])->name('regions.edit');
    Route::put('/wilayah/{id}', [KabupatenController::class, 'update'])->name('regions.update');
    Route::delete('/wilayah/{id}', [KabupatenController::class, 'destroy'])->name('regions.destroy');

    // CRUD Makanan Khas
    Route::get('/makanan', [KulinerKhasController::class, 'index'])->name('foods.index');
    Route::get('/makanan/tambah', [KulinerKhasController::class, 'create'])->name('foods.create');
    Route::post('/makanan', [KulinerKhasController::class, 'store'])->name('foods.store');
    Route::get('/makanan/{id}/edit', [KulinerKhasController::class, 'edit'])->name('foods.edit');
    Route::put('/makanan/{id}', [KulinerKhasController::class, 'update'])->name('foods.update');
    Route::delete('/makanan/{id}', [KulinerKhasController::class, 'destroy'])->name('foods.destroy');

    // CRUD Toko / Rumah Makan
    Route::get('/toko', [TokoController::class, 'index'])->name('shops.index');
    Route::get('/toko/tambah', [TokoController::class, 'create'])->name('shops.create');
    Route::post('/toko', [TokoController::class, 'store'])->name('shops.store');
    Route::get('/toko/{id}/edit', [TokoController::class, 'edit'])->name('shops.edit');
    Route::put('/toko/{id}', [TokoController::class, 'update'])->name('shops.update');
    Route::delete('/toko/{id}', [TokoController::class, 'destroy'])->name('shops.destroy');
});
