<?php

namespace App\Http\Controllers\kepala;

use App\Http\Controllers\Controller;
use App\Models\LokasiWisata;
use Inertia\Inertia;

class KepalaController extends Controller
{
    public function index()
    {
        $lokasi = LokasiWisata::dataPeta();

        return Inertia::render('kepala/Index', [
            'lokasi' => $lokasi,
        ]);
    }

    public function print()
    {
        $lokasi = LokasiWisata::dataPeta();

        return Inertia::render('kepala/Print', [
            'lokasi' => $lokasi,
        ]);
    }
}
