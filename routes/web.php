<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Captain;

Route::get('/', function () {
    return view('welcome');
});

// ── Rutas Admin ──────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('tournaments', Admin\TournamentController::class);
    Route::resource('teams', Admin\TeamController::class);
    Route::resource('matches', Admin\MatchController::class);
    Route::post('tournaments/{tournament}/fixture', [Admin\TournamentController::class, 'generateFixture'])->name('tournaments.fixture');
    Route::get('tournaments/{tournament}/pdf/fixture', [Admin\PdfController::class, 'fixture'])->name('tournaments.pdf.fixture');
    Route::get('tournaments/{tournament}/pdf/standings', [Admin\PdfController::class, 'standings'])->name('tournaments.pdf.standings');
    Route::get('tournaments/{tournament}/chart-data', [Admin\DashboardController::class, 'chartData'])->name('chart-data');
    Route::resource('teams.players', Admin\PlayerController::class)
    ->except(['show']);
});

// ── Rutas Captain ─────────────────────────────────────────────
Route::middleware(['auth', 'role:captain'])->prefix('captain')->name('captain.')->group(function () {
    Route::get('/dashboard', [Captain\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/team', [Captain\TeamController::class, 'show'])->name('team.show');
});

Route::get('/dashboard', function () {
    if (auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    if (auth()->user()->hasRole('captain')) {
        return redirect()->route('captain.dashboard');
    }
    return redirect('/');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';