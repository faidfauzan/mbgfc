<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MatchdayController;
use App\Http\Controllers\MatchdayRegistrationController;
use App\Models\Member;
use App\Models\Matchday;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $totalMembers = Member::count();
    $totalMatchdays = Matchday::count();

    return view('dashboard', compact('totalMembers', 'totalMatchdays'));
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

    //route resource untuk matchday
    Route::middleware(['auth', 'captain'])->group(function () {
    Route::resource('matchdays', MatchdayController::class);
});

//route regitation matchday
Route::middleware(['auth'])->group(function () {
    // Route Pendaftaran Matchday (Bisa diakses Member & Captain)
    Route::post('/matchdays/{matchday}/register', [MatchdayRegistrationController::class, 'store'])->name('matchdays.register');
    Route::delete('/registrations/{registration}', [MatchdayRegistrationController::class, 'destroy'])->name('registrations.destroy');
    Route::get('/matchdays/{matchday}/participants', [MatchdayRegistrationController::class, 'show'])->name('matchdays.participants');
});

//UI members----------------------------------------------------------------------------

Route::middleware(['auth'])->group(function () {
    // Lihat semua matchday yang berstatus open
    Route::get('/jadwal-matchday', [App\Http\Controllers\MatchdayRegistrationController::class, 'index'])->name('matchday.member.index');

    // Proses daftar
    Route::post('/matchday/{matchday}/daftar', [App\Http\Controllers\MatchdayRegistrationController::class, 'daftar'])->name('matchday.member.daftar');

    // Proses batalkan
    Route::delete('/matchday-registration/{registration}/batal', [App\Http\Controllers\MatchdayRegistrationController::class, 'batal'])->name('matchday.member.batal');
});
