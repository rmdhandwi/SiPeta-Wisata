<?php

namespace App\Http\Controllers\kepala;

use App\Http\Controllers\Controller;
use App\Models\LokasiWisata;
use Illuminate\Http\Request;
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

    public function print(Request $request)
    {
        $selectedJenis = $request->input('jenis');

        $lokasi = LokasiWisata::dataPeta()
            ->filter(function ($item) use ($selectedJenis) {
                return $selectedJenis ? $item->jenis === $selectedJenis : true;
            })
            ->sortBy('rank')
            ->values();

        return Inertia::render('kepala/Print', [
            'lokasi' => $lokasi,
        ]);
    }
}
