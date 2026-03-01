<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');
Route::post('/stores/stock', [StoreController::class, 'storeStock'])->name('stores.stock.store')->middleware(['auth', 'admin']);
Route::put('/stores/stock/{stock}', [StoreController::class, 'updateStock'])->name('stores.stock.update')->middleware(['auth', 'admin']);
Route::delete('/stores/stock/{stock}', [StoreController::class, 'destroyStock'])->name('stores.stock.destroy')->middleware(['auth', 'admin']);

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::post('/products/material-types', [ProductController::class, 'storeMaterialType'])->name('products.material-types.store')->middleware(['auth', 'admin']);
Route::post('/products/manufacturers', [ProductController::class, 'storeManufacturer'])->name('products.manufacturers.store')->middleware(['auth', 'admin']);
Route::post('/products/materials', [ProductController::class, 'storeMaterial'])->name('products.materials.store')->middleware(['auth', 'admin']);
Route::put('/products/materials/{material}', [ProductController::class, 'updateMaterial'])->name('products.materials.update')->middleware(['auth', 'admin']);
Route::delete('/products/materials/{material}', [ProductController::class, 'destroyMaterial'])->name('products.materials.destroy')->middleware(['auth', 'admin']);

Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index')->middleware(['auth', 'admin']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
