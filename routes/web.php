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
    Route::post('tournaments/{tournament}/groups', [Admin\TournamentController::class, 'addGroup'])->name('tournaments.groups.store');
    Route::post('tournaments/{tournament}/groups/{group}/teams', [Admin\TournamentController::class, 'addTeamToGroup'])->name('tournaments.groups.teams.store');
    Route::delete('tournaments/{tournament}/groups/{group}/teams/{team}', [Admin\TournamentController::class, 'removeTeamFromGroup'])->name('tournaments.groups.teams.destroy');
    Route::post('tournaments/{tournament}/fixture', [Admin\TournamentController::class, 'generateFixture'])->name('tournaments.fixture');
    Route::post('tournaments/{tournament}/next-round', [Admin\TournamentController::class, 'generateNextRound'])->name('tournaments.next-round');
    Route::get('tournaments/{tournament}/pdf/fixture', [Admin\PdfController::class, 'fixture'])->name('tournaments.pdf.fixture');
    Route::get('tournaments/{tournament}/pdf/standings', [Admin\PdfController::class, 'standings'])->name('tournaments.pdf.standings');
    Route::get('tournaments/{tournament}/chart-data', [Admin\DashboardController::class, 'chartData'])->name('chart-data');
    Route::get('tournaments/{tournament}/bracket', [Admin\TournamentController::class, 'bracket'])->name('tournaments.bracket');
    Route::get('tournaments/{tournament}/pdf/bracket', [Admin\PdfController::class, 'bracket'])->name('tournaments.pdf.bracket');
    Route::resource('users', Admin\UserController::class);
    Route::resource('teams.players', Admin\PlayerController::class);
    Route::post('matches/{match}/predict', [Admin\MatchController::class, 'predict'])->name('matches.predict');
    Route::post('tournaments/{tournament}/simulate', [Admin\TournamentController::class, 'simulateRound'])->name('tournaments.simulate');
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