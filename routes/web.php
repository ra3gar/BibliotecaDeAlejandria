<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoanController;
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
        Route::patch('users/{user}/toggle-active',  [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::patch('users/{user}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');
        Route::resource('books',      BookController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('authors',    AuthorController::class);
        Route::resource('loans',      LoanController::class)->only(['index', 'show', 'destroy', 'create', 'store']);
        Route::patch('loans/{loan}/return', [LoanController::class, 'markReturned'])->name('loans.return');
    });

    // User
    Route::middleware('role:user')->group(function () {
        Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo');
        Route::get('/catalogo/categoria/{category}', [CatalogoController::class, 'byCategory'])->name('catalogo.categoria');
        Route::get('/catalogo/autor/{author}', [CatalogoController::class, 'byAuthor'])->name('catalogo.autor');
        Route::get('/libros/{book}', [CatalogoController::class, 'show'])->name('books.show');
        Route::get('/perfil', [ProfileController::class, 'index'])->name('profile');
    });
});
