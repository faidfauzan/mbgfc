<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MatchdayController;
use App\Http\Controllers\MatchdayRegistrationController;
use App\Http\Controllers\PrioritasController;
use App\Models\Member;
use App\Models\Matchday;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Route Dashboard Utama
Route::get('/dashboard', function () {
    $user = Auth::user();
    $totalMembers = Member::count();
    $totalMatchdays = Matchday::count();

    // PERBAIKAN: Menggunakan nama kolom baru (tanggal_berakhir_prioritas)
    $totalPrioritas = Member::whereNotNull('tanggal_berakhir_prioritas')
        ->where('tanggal_berakhir_prioritas', '>', now())
        ->count();
        
    $totalReguler = $totalMembers - $totalPrioritas;

    // Ambil setting kuota
    $maxQuota = (int) (Setting::where('key', 'max_prioritas_quota')->value('value') ?? 15);
    $activePrioritasCount = $totalPrioritas;
    $isQuotaFull = $activePrioritasCount >= $maxQuota;

    // Data member logged in
    $member = Member::where('user_id', $user->id)->first();

    return view('dashboard', compact(
        'totalMembers',
        'totalMatchdays',
        'totalPrioritas',
        'totalReguler',
        'maxQuota',
        'activePrioritasCount',
        'isQuotaFull',
        'member'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

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

// Route buat Mengelola Peserta Matchday
Route::middleware(['auth', 'captain'])->group(function () {
    Route::get('/matchdays/{matchday}/peserta', [MatchdayController::class, 'peserta'])->name('matchdays.peserta');
    Route::delete('/matchday-registration/{registration}/batalkan-paksa', [MatchdayController::class, 'batalkanPaksa'])->name('matchdays.batalkan-paksa');
});

// Route halaman info match
Route::middleware(['auth'])->group(function () {
    Route::get('/jadwal-matchday/{matchday}', [MatchdayRegistrationController::class, 'show'])
        ->name('matchday.member.show');

    Route::get('/jadwal-matchday/{matchday}/daftar', [MatchdayRegistrationController::class, 'create'])
        ->name('matchday.member.create-form');
});

// Route Pendaftaran Prioritas (Member & Captain)
Route::middleware(['auth'])->group(function () {
    Route::get('/prioritas/daftar', [PrioritasController::class, 'create'])->name('prioritas.create');
    Route::post('/prioritas/daftar', [PrioritasController::class, 'store'])->name('prioritas.store');
    Route::post('/admin/prioritas/update-quota', [PrioritasController::class, 'updateQuota'])->name('admin.prioritas.updateQuota');
});