<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\User\CatalogoController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;

// Root → login
Route::get('/', fn () => redirect()->route('login'));

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('users',      UserController::class);
        Route::resource('books',      BookController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('authors',    AuthorController::class);
    });

    // User
    Route::middleware('role:user')->group(function () {
        Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo');
        Route::get('/libros/{book}', [CatalogoController::class, 'show'])->name('books.show');
        Route::get('/perfil', [ProfileController::class, 'index'])->name('profile');
    });
});
