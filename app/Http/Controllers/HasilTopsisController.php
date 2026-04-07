<?php

namespace App\Http\Controllers;

use App\Models\HasilTopsis;
use App\Models\Kriteria;
use App\Models\LokasiWisata;
use App\Models\NilaiAlternatif;
use App\Models\Subkriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class HasilTopsisController extends Controller
{

    public function index()
    {
        // helper format 3 desimal (HANYA UNTUK OUTPUT)
        function f3($val)
        {
            return round($val, 3);
        }

        $kriteriaList = Kriteria::select('id_kriteria', 'nama_kriteria', 'bobot_kriteria', 'tipe_kriteria')
            ->orderBy('id_kriteria')
            ->get();

        // ===============================
        // VALIDASI
        // ===============================
        $totalBobot = $kriteriaList->sum('bobot_kriteria');
        if ($totalBobot != 100) {
            return Redirect::route('admin.kriteria.index')
                ->with('error', 'Total bobot harus 100, sekarang: ' . $totalBobot);
        }

        // ===============================
        // AMBIL DATA
        // ===============================
        $alternatif = NilaiAlternatif::with([
            'lokasi.jenisWisata',
            'subkriteria.kriteria'
        ])->get()->groupBy('lokasi_wisata_id');

        $lokasiMap = LokasiWisata::pluck('nama_lokasi_wisata', 'id_lokasi_wisata')->toArray();

        // ===============================
        // 1. MATRIX (TANPA PEMBULATAN)
        // ===============================
        $matrix = [];

        foreach ($alternatif as $lokasiId => $items) {

            $lokasi = $items->first()->lokasi;
            $matrix[$lokasiId]['alternatif'] = $lokasi->nama_lokasi_wisata;

            foreach ($kriteriaList as $k) {

                $nama = $k->nama_kriteria;

                if ($nama === 'Jenis wisata') {
                    $jenisNama = $lokasi->jenisWisata?->nama_jenis_wisata;
                    $sub = Subkriteria::where('nama_subkriteria', $jenisNama)->first();

                    $matrix[$lokasiId][$nama] = $sub?->bobot_subkriteria ?? 0;
                } else {
                    $vals = $items
                        ->where('subkriteria.kriteria_id', $k->id_kriteria)
                        ->pluck('nilai')
                        ->toArray();

                    $matrix[$lokasiId][$nama] =
                        count($vals) ? array_sum($vals) / count($vals) : 0;
                }
            }
        }

        uasort($matrix, fn($a, $b) => strcmp($a['alternatif'], $b['alternatif']));

        // ===============================
        // 2. NORMALISASI (TANPA PEMBULATAN)
        // ===============================
        $normalisasi = [];
        $pembagiList = [];

        foreach ($kriteriaList as $k) {

            $nama = $k->nama_kriteria;

            $sum = 0;
            foreach ($matrix as $row) {
                $sum += pow($row[$nama], 2);
            }

            $pembagi = sqrt($sum ?: 1);
            $pembagiList[$nama] = $pembagi;

            foreach ($matrix as $id => $row) {
                $normalisasi[$id]['alternatif'] = $row['alternatif'];
                $normalisasi[$id][$nama] = $row[$nama] / $pembagi;
            }
        }

        uasort($normalisasi, fn($a, $b) => strcmp($a['alternatif'], $b['alternatif']));

        // ===============================
        // 3. PEMBOBOTAN (TANPA PEMBULATAN)
        // ===============================
        $pembobotan = [];

        foreach ($normalisasi as $id => $row) {

            $pembobotan[$id]['alternatif'] = $row['alternatif'];

            foreach ($kriteriaList as $k) {
                $nama = $k->nama_kriteria;
                $bobot = $k->bobot_kriteria / 100;

                $pembobotan[$id][$nama] = $row[$nama] * $bobot;
            }
        }

        uasort($pembobotan, fn($a, $b) => strcmp($a['alternatif'], $b['alternatif']));

        // ===============================
        // 4. SOLUSI IDEAL (TANPA PEMBULATAN)
        // ===============================
        $idealPositif = [];
        $idealNegatif = [];

        foreach ($kriteriaList as $k) {

            $nama = $k->nama_kriteria;
            $values = array_column($pembobotan, $nama);

            if ($k->tipe_kriteria === 'Benefit') {
                $idealPositif[$nama] = max($values);
                $idealNegatif[$nama] = min($values);
            } else {
                $idealPositif[$nama] = min($values);
                $idealNegatif[$nama] = max($values);
            }
        }

        ksort($idealPositif);
        ksort($idealNegatif);

        // ===============================
        // 5. JARAK (TANPA PEMBULATAN)
        // ===============================
        $jarak = ['positif' => [], 'negatif' => []];

        foreach ($pembobotan as $id => $row) {

            $sumPlus = 0;
            $sumMin = 0;

            foreach ($kriteriaList as $k) {
                $nama = $k->nama_kriteria;

                $sumPlus += pow($row[$nama] - $idealPositif[$nama], 2);
                $sumMin += pow($row[$nama] - $idealNegatif[$nama], 2);
            }

            $namaLokasi = $row['alternatif'];

            $jarak['positif'][$namaLokasi] = sqrt($sumPlus);
            $jarak['negatif'][$namaLokasi] = sqrt($sumMin);
        }

        ksort($jarak['positif']);
        ksort($jarak['negatif']);

        // ===============================
        // 6. PREFERENSI (TANPA PEMBULATAN)
        // ===============================
        $preferensi = [];

        foreach ($jarak['positif'] as $namaLokasi => $dp) {

            $dm = $jarak['negatif'][$namaLokasi];

            $preferensi[$namaLokasi] =
                ($dp + $dm > 0) ? $dm / ($dp + $dm) : 0;
        }

        ksort($preferensi);

        // ===============================
        // 7. RANKING
        // ===============================
        arsort($preferensi);

        $peringkat = [];
        $rank = 1;

        foreach ($preferensi as $namaLokasi => $val) {
            $peringkat[] = [
                'id' => $namaLokasi,
                'nilai' => f3($val), // 🔥 dibulatkan di sini saja
                'rank' => $rank++,
            ];
        }

        // ===============================
        // 8. SIMPAN
        // ===============================
        DB::table('hasil_topsis')->truncate();

        foreach ($peringkat as $entry) {

            $lokasiId = array_search($entry['id'], $lokasiMap, true);

            HasilTopsis::create([
                'lokasi_wisata_id' => $lokasiId,
                'jarak_positif' => f3($jarak['positif'][$entry['id']]),
                'jarak_negative' => f3($jarak['negatif'][$entry['id']]),
                'tipe_preferensi' => $entry['nilai'],
                'rangking' => $entry['rank'],
            ]);
        }

        // ===============================
        // RETURN (SEMUA DIFORMAT DI SINI)
        // ===============================
        return Inertia::render('admin/Topsis/Index', [
            'matrixKeputusan' => collect($matrix)->map(
                fn($row) =>
                collect($row)->map(
                    fn($v, $k) =>
                    $k === 'alternatif' ? $v : f3($v)
                )
            )->values(),

            'normalisasi' => collect($normalisasi)->map(
                fn($row) =>
                collect($row)->map(
                    fn($v, $k) =>
                    $k === 'alternatif' ? $v : f3($v)
                )
            )->values(),

            'bobotMatriks' => collect($pembobotan)->map(
                fn($row) =>
                collect($row)->map(
                    fn($v, $k) =>
                    $k === 'alternatif' ? $v : f3($v)
                )
            )->values(),

            'solusiIdeal' => [
                'positif' => collect($idealPositif)->map(fn($v) => f3($v)),
                'negatif' => collect($idealNegatif)->map(fn($v) => f3($v)),
            ],

            'jarak' => [
                'positif' => collect($jarak['positif'])->map(fn($v) => f3($v)),
                'negatif' => collect($jarak['negatif'])->map(fn($v) => f3($v)),
            ],

            'preferensi' => collect($preferensi)->map(fn($v) => f3($v)),

            'peringkat' => $peringkat,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function potensi()
    {
        // Ambil semua hasil TOPSIS yang sudah diurut berdasarkan ranking
        $hasil = HasilTopsis::hasilAll();

        // Format data untuk ditampilkan di frontend
        $data = $hasil->map(function ($item) {
            return [
                'alternatif'       => $item->lokasiWisata->nama_lokasi_wisata ?? 'Tidak diketahui',
                'jarak_positif'    => $item->jarak_positif,
                'jarak_negatif'    => $item->jarak_negative,
                'preferensi'       => $item->tipe_preferensi,
                'rangking'         => $item->rangking,
            ];
        });

        return Inertia::render('admin/Topsis/Potensi', [
            'hasil' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function pemetaan()
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
                'preferensi' => $lokasi->hasilTopsis?->tipe_preferensi,
            ];
        });

        return Inertia::render('admin/Topsis/Pemetaan', [
            'lokasi' => $lokasi,
        ]);
    }
}
