<?php

namespace App\Http\Controllers\inverstor;

use App\Http\Controllers\Controller;
use App\Models\LokasiWisata;
use Inertia\Inertia;

class InvestorController extends Controller
{
    public function index()
    {

        $data = LokasiWisata::with([
            'nilaiAlternatif.subkriteria'
        ])->get();

        $lokasi = $data->map(function ($lokasi) {

            // ambil semua subkriteria per lokasi
            $subs = $lokasi->nilaiAlternatif->pluck('subkriteria');

            return [
                'nama' => $lokasi->nama_lokasi_wisata,
                'jenis' => $lokasi->jenisWisata?->nama_jenis_wisata,

                'latitude' => $lokasi->latitude,
                'longitude' => $lokasi->longitude,

                // 🔥 ambil nama bukan angka
                'fasilitas' => $subs
                    ->where('kriteria.nama_kriteria', 'Fasilitas')
                    ->pluck('nama_subkriteria')
                    ->implode(', '),

                'transportasi' => $subs
                    ->where('kriteria.nama_kriteria', 'Transportasi')
                    ->pluck('nama_subkriteria')
                    ->implode(', '),

                'keamanan' => $subs
                    ->where('kriteria.nama_kriteria', 'Keamanan')
                    ->pluck('nama_subkriteria')
                    ->implode(', '),

                'akses_lokasi' => $subs
                    ->where('kriteria.nama_kriteria', 'Akses lokasi')
                    ->pluck('nama_subkriteria')
                    ->implode(', '),

                // ranking & preferensi (kalau ada)
                'rank' => $lokasi->hasilTopsis?->rangking,
            ];
        });

        return Inertia::render('investor/Index', [
            'lokasi' => $lokasi,
        ]);
    }
}
