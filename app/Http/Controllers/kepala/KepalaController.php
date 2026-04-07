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

        return Inertia::render('kepala/Index', [
            'lokasi' => $lokasi,
        ]);
    }

    public function print(Request $request)
    {
        $selectedJenis = $request->input('jenis');

        // ===============================
        // AMBIL DATA + RELASI LENGKAP
        // ===============================
        $data = LokasiWisata::with([
            'jenisWisata',
            'hasilTopsis',
            'nilaiAlternatif.subkriteria.kriteria'
        ])->get();

        // ===============================
        // MAPPING DATA
        // ===============================
        $lokasi = $data->map(function ($lokasi) {

            $subs = $lokasi->nilaiAlternatif->pluck('subkriteria');

            // 🔥 group berdasarkan kriteria
            $group = $subs->groupBy(fn($s) => $s->kriteria->nama_kriteria ?? '');

            return [
                'nama' => $lokasi->nama_lokasi_wisata,
                'jenis' => $lokasi->jenisWisata?->nama_jenis_wisata,

                'latitude' => $lokasi->latitude,
                'longitude' => $lokasi->longitude,

                'fasilitas' => ($group['Fasilitas'] ?? collect())
                    ->pluck('nama_subkriteria')
                    ->implode(', '),

                'transportasi' => ($group['Transportasi'] ?? collect())
                    ->pluck('nama_subkriteria')
                    ->implode(', '),

                'keamanan' => ($group['Keamanan'] ?? collect())
                    ->pluck('nama_subkriteria')
                    ->implode(', '),

                'akses_lokasi' => ($group['Akses lokasi'] ?? collect())
                    ->pluck('nama_subkriteria')
                    ->implode(', '),

                'rank' => $lokasi->hasilTopsis?->rangking,
                'preferensi' => $lokasi->hasilTopsis?->tipe_preferensi,
            ];
        })

            // ===============================
            // FILTER JENIS
            // ===============================
            ->filter(function ($item) use ($selectedJenis) {
                return $selectedJenis ? $item['jenis'] === $selectedJenis : true;
            })

            // ===============================
            // SORT RANKING (AMAN)
            // ===============================
            ->sortBy(function ($item) {
                return $item['rank'] ?? 9999;
            })

            ->values();

        // ===============================
        // RETURN
        // ===============================
        return Inertia::render('kepala/Print', [
            'lokasi' => $lokasi,
        ]);
    }
}
