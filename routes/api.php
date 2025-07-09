<?php

use App\Models\JenisWisata;
use App\Models\Kriteria;
use App\Models\LokasiWisata;
use App\Models\NilaiAlternatif;
use App\Models\Subkriteria;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/api/jenis-wisata', [JenisWisata::class, 'jenisWisataAll']);
    Route::get('/api/lokasi-wisata', [LokasiWisata::class, 'lokasiWisataAll']);
    Route::get('/api/kriteria', [Kriteria::class, 'kriteriaAll']);
    Route::get('/api/subkriteria', [Subkriteria::class, 'subkriteriaAll']);
    Route::get('/api/alternatif', [NilaiAlternatif::class, 'alternatifAll']);
});
