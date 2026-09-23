<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MatchdayController;
use App\Http\Controllers\MatchdayRegistrationController;
use App\Http\Controllers\PrioritasController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberHistoryController;

/*
|--------------------------------------------------------------------------
| 1. ROUTE HALAMAN PUBLIK / LANDING PAGE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
   return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| 2. ROUTE UTAMA USER & PROFIL (BISA DIAKSES SEMUA AKUN LOGIN)
|--------------------------------------------------------------------------
*/
// Dashboard Utama
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Manajemen Profil User
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Import Route Autentikasi Laravel Breeze (Login, Register, Reset Password)
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| 3. ROUTE PERINGATAN MEMBER PENDING
|--------------------------------------------------------------------------
*/
// Halaman "Akses Dikunci" saat member status = pending mencoba buka Matchday
Route::get('/matchday-pending', function () {
    return view('matchdays.pending');
})->middleware(['auth'])->name('matchdays.pending');

/*
|--------------------------------------------------------------------------
| 4. ROUTE TERKUNCI (HANYA UNTUK MEMBER YANG SUDAH DI-ACC / ACTIVE)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'account.active'])->group(function () {

    // --- FITUR UTAMA MATCHDAY (MEMBER) ---
    Route::get('/jadwal-matchday', [MatchdayRegistrationController::class, 'index'])->name('matchday.member.index');
    Route::get('/jadwal-matchday/{matchday}', [MatchdayRegistrationController::class, 'show'])->name('matchday.member.show');
    Route::get('/jadwal-matchday/{matchday}/daftar', [MatchdayRegistrationController::class, 'create'])->name('matchday.member.create-form');
    Route::post('/matchdays/{matchday}/register', [MatchdayRegistrationController::class, 'store'])->name('matchday.member.store');
    Route::get('/matchdays/{matchday}/participants', [MatchdayRegistrationController::class, 'show'])->name('matchdays.participants');
    Route::delete('/matchday-registration/{registration}/batal', [MatchdayRegistrationController::class, 'batal'])->name('matchday.member.batal');

    // --- FITUR PENDAFTARAN MEMBER PRIORITAS ---
    Route::get('/prioritas/daftar', [PrioritasController::class, 'create'])->name('prioritas.create');
    Route::post('/prioritas/daftar', [PrioritasController::class, 'store'])->name('prioritas.store');
    Route::get('/prioritas/history', [PrioritasController::class, 'history'])->name('prioritas.history');

});

/*
|--------------------------------------------------------------------------
| 5. ROUTE KHUSUS ADMIN / CAPTAIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'captain'])->group(function () {
    // Kelola Member & Kelola Matchday
    Route::resource('members', MemberController::class)->except(['show']);
    Route::resource('matchdays', MatchdayController::class);
    Route::resource('announcements', AnnouncementController::class);

    // Fitur Kelola Peserta Matchday
    Route::get('/matchdays/{matchday}/peserta', [MatchdayController::class, 'peserta'])->name('matchdays.peserta');
    Route::delete('/matchday-registration/{registration}/batalkan-paksa', [MatchdayController::class, 'batalkanPaksa'])->name('matchdays.batalkan-paksa');
    Route::post('/admin/prioritas/update-quota', [PrioritasController::class, 'updateQuota'])->name('admin.prioritas.updateQuota');

    // Route Kelola ACC and reject Member (Khusus Captain/Admin)
    Route::get('/admin/members/pending', [MemberController::class, 'pendingList'])->name('members.pending');
    Route::patch('/admin/members/{id}/approve', [MemberController::class, 'approve'])->name('members.approve');
    Route::delete('/admin/members/{id}/reject', [MemberController::class, 'reject'])->name('members.reject');

    // Test Hak Akses Captain
    Route::get('/captain/test', function () {
        return 'Selamat datang, Captain! Kamu berhasil akses halaman khusus captain.';
    });
});

/*
|--------------------------------------------------------------------------
| 6. ROUTE PENGUMUMAN & HISTORI
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Pengumuman untuk Member
    Route::get('/pengumuman', [AnnouncementController::class, 'memberIndex'])->name('announcements.member.index');

    // Histori Pertandingan & Pembayaran
    Route::get('/history/matchdays', [MatchdayController::class, 'historyMatchday'])->name('history.matchdays');
    Route::get('/history/member/{user}', [MatchdayController::class, 'historyMember'])->name('history.member');
    Route::get('/member/history', [MemberHistoryController::class, 'index'])->name('member.history');
});

// Detail Member
Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');

/*
|--------------------------------------------------------------------------
| 7. ROUTE PWA & ERROR
|--------------------------------------------------------------------------
*/
Route::get('/offline', function () {
    return view('errors.offline');
});