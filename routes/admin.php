<?php

use App\Http\Controllers\HasilTopsisController;
use App\Http\Controllers\JenisWisataController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\LokasiWisataController;
use App\Http\Controllers\NilaiAlternatifController;
use App\Http\Controllers\SubkriteriaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:1'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::prefix('jeniswisata')
            ->name('jeniswisata.')
            ->group(function () {

                // Lihat semua data
                Route::get('/', [JenisWisataController::class, 'index'])->name('index');

                // Tambah data
                Route::get('/create', [JenisWisataController::class, 'create'])->name('create');
                Route::post('/', [JenisWisataController::class, 'store'])->name('store');

                // Edit data tanpa ID di URL
                Route::post('/edit/init', [JenisWisataController::class, 'editInit'])->name('edit.init');
                Route::get('/edit', [JenisWisataController::class, 'edit'])->name('edit');
                Route::put('/{jenisWisata}', [JenisWisataController::class, 'update'])->name('update');

                // Hapus data
                Route::delete('/{jenisWisata}', [JenisWisataController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('lokasiwisata')
            ->name('lokasiwisata.')
            ->group(function () {

                // Lihat semua data
                Route::get('/', [LokasiWisataController::class, 'index'])->name('index');

                // Tambah data
                Route::get('/create', [LokasiWisataController::class, 'create'])->name('create');
                Route::post('/', [LokasiWisataController::class, 'store'])->name('store');
                Route::post('/colom', [LokasiWisataController::class, 'colom'])->name('colom');

                // Edit data tanpa ID di URL
                Route::post('/edit/init', [LokasiWisataController::class, 'editInit'])->name('edit.init');
                Route::get('/edit', [LokasiWisataController::class, 'edit'])->name('edit');
                Route::put('/{lokasiwisata}', [LokasiWisataController::class, 'update'])->name('update');

                // Hapus data
                Route::delete('/{lokasiwisata}', [LokasiWisataController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('kriteria')
            ->name('kriteria.')
            ->group(function () {

                // Lihat semua data
                Route::get('/', [KriteriaController::class, 'index'])->name('index');

                // Tambah data
                Route::get('/create', [KriteriaController::class, 'create'])->name('create');
                Route::post('/', [KriteriaController::class, 'store'])->name('store');

                // Edit data tanpa ID di URL
                Route::post('/edit/init', [KriteriaController::class, 'editInit'])->name('edit.init');
                Route::get('/edit', [KriteriaController::class, 'edit'])->name('edit');
                Route::put('/{kriteria}', [KriteriaController::class, 'update'])->name('update');

                // Hapus data
                Route::delete('/{kriteria}', [KriteriaController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('subkriteria')
            ->name('subkriteria.')
            ->group(function () {

                // Lihat semua data
                Route::get('/', [SubkriteriaController::class, 'index'])->name('index');

                // Tambah data
                Route::get('/create', [SubkriteriaController::class, 'create'])->name('create');
                Route::post('/', [SubkriteriaController::class, 'store'])->name('store');

                // Edit data tanpa ID di URL
                Route::post('/edit/init', [SubkriteriaController::class, 'editInit'])->name('edit.init');
                Route::get('/edit', [SubkriteriaController::class, 'edit'])->name('edit');
                Route::put('/{subkriteria}', [SubkriteriaController::class, 'update'])->name('update');

                // Hapus data
                Route::delete('/{subkriteria}', [SubkriteriaController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('alternatif')
            ->name('alternatif.')
            ->group(function () {

                // Lihat semua data
                Route::get('/', [NilaiAlternatifController::class, 'index'])->name('index');

                // Tambah data
                Route::get('/create', [NilaiAlternatifController::class, 'create'])->name('create');
                Route::post('/', [NilaiAlternatifController::class, 'store'])->name('store');

                // Edit data tanpa ID di URL
                Route::post('/edit/init', [NilaiAlternatifController::class, 'editInit'])->name('edit.init');
                Route::get('/edit', [NilaiAlternatifController::class, 'edit'])->name('edit');
                Route::put('/{lokasi_wisata_id}', [NilaiAlternatifController::class, 'update'])->name('update');


                // Hapus data
                Route::delete('/{lokasi_wisata_id}', [NilaiAlternatifController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('topsis')
            ->name('topsis.')
            ->group(function () {
                Route::get('/', [HasilTopsisController::class, 'index'])->name('index');
            });

        Route::get('/potensi', [HasilTopsisController::class, 'potensi'])->name('potensi');
        Route::get('/pemetaan', [HasilTopsisController::class, 'pemetaan'])->name('pemetaan');
    });
