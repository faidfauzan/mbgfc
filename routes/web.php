<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MatchdayController;
use App\Http\Controllers\MatchdayRegistrationController;
use App\Http\Controllers\PrioritasController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DashboardController; // 👈 Panggil Controller Dashboard

Route::get('/', function () {
    return view('welcome');
});

// Route Dashboard Utama (Memanggil DashboardController)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/captain/test', function () {
    return 'Selamat datang, Captain! Kamu berhasil akses halaman khusus captain.';
})->middleware(['auth', 'captain']);

require __DIR__ . '/auth.php';

Route::resource('members', MemberController::class)
    ->except(['show'])
    ->middleware(['auth', 'captain']);

// Route resource untuk matchday
Route::middleware(['auth', 'captain'])->group(function () {
    Route::resource('matchdays', MatchdayController::class);
});

// Route registration matchday
Route::middleware(['auth'])->group(function () {
    Route::post('/matchdays/{matchday}/register', [MatchdayRegistrationController::class, 'store'])
        ->name('matchday.member.store');

    Route::get('/matchdays/{matchday}/participants', [MatchdayRegistrationController::class, 'show'])
        ->name('matchdays.participants');
});

// UI members
Route::middleware(['auth'])->group(function () {
    Route::get('/jadwal-matchday', [MatchdayRegistrationController::class, 'index'])->name('matchday.member.index');
    Route::delete('/matchday-registration/{registration}/batal', [MatchdayRegistrationController::class, 'batal'])->name('matchday.member.batal');
});

// Route buat Mengelola Peserta Matchday (Captain)
Route::middleware(['auth', 'captain'])->group(function () {
    Route::get('/matchdays/{matchday}/peserta', [MatchdayController::class, 'peserta'])->name('matchdays.peserta');
    Route::delete('/matchday-registration/{registration}/batalkan-paksa', [MatchdayController::class, 'batalkanPaksa'])->name('matchdays.batalkan-paksa');
    Route::post('/admin/prioritas/update-quota', [PrioritasController::class, 'updateQuota'])->name('admin.prioritas.updateQuota'); // 👈 Dipindahkan ke sini demi keamanan
});

// Route halaman info match
Route::middleware(['auth'])->group(function () {
    Route::get('/jadwal-matchday/{matchday}', [MatchdayRegistrationController::class, 'show'])
        ->name('matchday.member.show');

    Route::get('/jadwal-matchday/{matchday}/daftar', [MatchdayRegistrationController::class, 'create'])
        ->name('matchday.member.create-form');
});

// Route Pendaftaran Prioritas (Member)
Route::middleware(['auth'])->group(function () {
    Route::get('/prioritas/daftar', [PrioritasController::class, 'create'])->name('prioritas.create');
    Route::post('/prioritas/daftar', [PrioritasController::class, 'store'])->name('prioritas.store');
});

// Group khusus Captain
Route::middleware(['auth', 'captain'])->group(function () {
    Route::resource('announcements', AnnouncementController::class);
});

// Route buat Member untuk baca histori pengumuman 
Route::middleware(['auth'])->group(function () {
    Route::get('/pengumuman', [AnnouncementController::class, 'memberIndex'])->name('announcements.member.index');
});