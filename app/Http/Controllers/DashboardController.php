<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\LokasiWisata;
use App\Models\Subkriteria;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'jumlahKriteria' => Kriteria::count(),
            'jumlahSubkriteria' => Subkriteria::count(),
            'jumlahLokasiWisata' => LokasiWisata::count(),
        ]);
    }
}
