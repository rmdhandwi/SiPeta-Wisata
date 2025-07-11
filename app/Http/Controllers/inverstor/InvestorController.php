<?php

namespace App\Http\Controllers\inverstor;

use App\Http\Controllers\Controller;
use App\Models\LokasiWisata;
use Inertia\Inertia;

class InvestorController extends Controller
{
    public function index()
    {

        $lokasi = LokasiWisata::dataPeta();

        return Inertia::render('investor/Index', [
            'lokasi' => $lokasi,
        ]);
    }
}
