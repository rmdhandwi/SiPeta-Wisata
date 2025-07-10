<?php

namespace App\Http\Controllers\inverstor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InvestorController extends Controller
{
    public function index()
    {

        $lokasi = DB::table('lokasi_wisata as lw')
            ->join('jenis_wisata as jw', 'lw.jenis_wisata_id', '=', 'jw.id_jenis_wisata')
            ->join('hasil_topsis as ht', 'lw.id_lokasi_wisata', '=', 'ht.lokasi_wisata_id')
            ->select(
                'lw.nama_lokasi_wisata as nama',
                'jw.nama_jenis_wisata as jenis',
                'lw.*',
                'ht.rangking as rank',
                'ht.tipe_preferensi as preferensi'
            )
            ->get();



        return Inertia::render('investor/Index', [
            'lokasi' => $lokasi,
        ]);
    }
}
