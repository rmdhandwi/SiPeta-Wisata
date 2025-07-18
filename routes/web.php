<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\inverstor\InvestorController;
use App\Http\Controllers\kepala\KepalaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');


Route::middleware('guest')->group(
    function () {
        Route::get('/investor', [InvestorController::class, 'index'])->name('investor.index');
    }
);


Route::middleware(['auth', 'verified', 'role:2'])->group(
    function () {
        Route::get('/laporan', [KepalaController::class, 'index'])->name('laporan.index');
        Route::post('/laporan/cetak', [KepalaController::class, 'print'])->name('laporan.cetak');
    }
);


Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:1'])
    ->name('dashboard');


require __DIR__ . '/admin.php';
require __DIR__ . '/api.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
