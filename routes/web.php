<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/captain/test', function () {
    return 'Selamat datang, Captain! Kamu berhasil akses halaman khusus captain.';
})->middleware(['auth', 'captain']);

require __DIR__.'/auth.php';

//
Route::resource('members', MemberController::class)
    ->except(['show', 'destroy'])
    ->middleware(['auth', 'captain']);
