<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UsersController;
use App\Http\Controllers\DashBoard\DashBoardController;
use App\Http\Controllers\Reservations\ShoppingCardController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::middleware('auth', 'user_active')->group(function () {
    Route::get('/dashboard', [DashBoardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

    Route::post('/search', [DashBoardController::class, 'search'])->name('search');
    Route::get('/show/details/search/{id}/{model}', [DashBoardController::class, 'details'])->name('detail.serch');

    Route::get('/valid/product', [ShoppingCardController::class, 'index'])->name('reservations.index');
    Route::get('/register/order', [ShoppingCardController::class, 'register'])->name('reservations.register');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::prefix('users')->group(function () {

        Route::get('/create', [UsersController::class, 'create'])->name('users.create');
        Route::get('/', [UsersController::class, 'index'])->name('users');
        Route::get('/{id}', [UsersController::class, 'show'])->name('users.show');
    });

    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
