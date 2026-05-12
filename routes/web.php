<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeSlideController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{category}', [CatalogController::class, 'category'])->name('catalog.category');
Route::get('/catalog/{category}/{sectionSlug}', [CatalogController::class, 'section'])->name('catalog.section');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{material}', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/{material}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{material}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

Route::get('/account', [AccountController::class, 'index'])->name('account.index')->middleware('auth');

Route::get('/products', [ProductController::class, 'index'])->name('products.index')->middleware(['auth', 'role:content_manager']);
Route::put('/products/home-slides', [HomeSlideController::class, 'update'])->name('products.home-slides.update')->middleware(['auth', 'role:content_manager']);
Route::post('/products/material-types', [ProductController::class, 'storeMaterialType'])->name('products.material-types.store')->middleware(['auth', 'role:content_manager']);
Route::delete('/products/material-types/{materialType}', [ProductController::class, 'destroyMaterialType'])->name('products.material-types.destroy')->middleware(['auth', 'role:content_manager']);
Route::post('/products/manufacturers', [ProductController::class, 'storeManufacturer'])->name('products.manufacturers.store')->middleware(['auth', 'role:content_manager']);
Route::put('/products/manufacturers/{manufacturer}', [ProductController::class, 'updateManufacturer'])->name('products.manufacturers.update')->middleware(['auth', 'role:content_manager']);
Route::delete('/products/manufacturers/{manufacturer}', [ProductController::class, 'destroyManufacturer'])->name('products.manufacturers.destroy')->middleware(['auth', 'role:content_manager']);
Route::post('/products/materials', [ProductController::class, 'storeMaterial'])->name('products.materials.store')->middleware(['auth', 'role:content_manager']);
Route::put('/products/materials/{material}', [ProductController::class, 'updateMaterial'])->name('products.materials.update')->middleware(['auth', 'role:content_manager']);
Route::delete('/products/materials/{material}', [ProductController::class, 'destroyMaterial'])->name('products.materials.destroy')->middleware(['auth', 'role:content_manager']);

Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index')->middleware(['auth', 'role:director,manager']);
Route::get('/director/dashboard', [DirectorController::class, 'dashboard'])->name('director.dashboard')->middleware(['auth', 'role:director']);
Route::put('/director/users/{user}', [DirectorController::class, 'updateCredentials'])->name('director.users.update')->middleware(['auth', 'role:director']);

Route::get('/manager/orders', [ManagerController::class, 'orders'])->name('manager.orders')->middleware(['auth', 'role:manager']);
Route::put('/manager/orders/{order}', [ManagerController::class, 'updateStatus'])->name('manager.orders.update')->middleware(['auth', 'role:manager']);
Route::get('/manager/orders/export', [ManagerController::class, 'exportOrders'])->name('manager.orders.export')->middleware(['auth', 'role:manager']);
Route::get('/manager/feedback', [ManagerController::class, 'feedback'])->name('manager.feedback')->middleware(['auth', 'role:manager']);
Route::put('/manager/feedback/{contactMessage}', [ManagerController::class, 'replyToContact'])->name('manager.feedback.reply')->middleware(['auth', 'role:manager']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
