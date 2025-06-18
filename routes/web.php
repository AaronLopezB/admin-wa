<?php

use App\Http\Controllers\DashBoard\DashBoardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Reservations\ShoppingCardController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::middleware('auth', 'user_active')->group(function () {
    Route::get('/dashboard', [DashBoardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('/valid/product', [ShoppingCardController::class, 'index'])->name('reservations.index');
    Route::get('/register/order', [ShoppingCardController::class, 'register'])->name('reservations.register');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
