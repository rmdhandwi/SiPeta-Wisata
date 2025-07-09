<?php

namespace App\Http\Controllers;

use App\Models\HasilTopsis;
use App\Models\Kriteria;
use App\Models\LokasiWisata;
use App\Models\NilaiAlternatif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class HasilTopsisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kriteria = Kriteria::with('subkriteria')->get();

        // Validasi: jika jumlah kriteria < 5
        if ($kriteria->count() < 5) {
            return Redirect::route('admin.kriteria.index')
                ->with('error', 'Jumlah kriteria kurang dari 5. Tidak bisa melakukan perhitungan TOPSIS.');
        }

        $alternatif = NilaiAlternatif::with(['lokasi', 'subkriteria', 'subkriteria.kriteria'])
            ->get()
            ->groupBy('lokasi_wisata_id');

        $lokasiMap = LokasiWisata::pluck('nama_lokasi_wisata', 'id_lokasi_wisata')->toArray();

        // Ambil nama kriteria
        $namaKriteriaMap = $kriteria->pluck('nama_kriteria', 'id_kriteria')->toArray();

        // Matriks Keputusan
        $matrixKeputusan = [];
        foreach ($alternatif as $lokasiId => $items) {
            foreach ($items as $item) {
                $kriteriaId = $item->subkriteria->kriteria_id;
                $namaKriteria = $namaKriteriaMap[$kriteriaId] ?? "K{$kriteriaId}";
                $matrixKeputusan[$lokasiId]['alternatif'] = $item->lokasi->nama_lokasi_wisata;
                $matrixKeputusan[$lokasiId][$namaKriteria] = $item->nilai;
            }
        }

        if (empty($matrixKeputusan)) {
            return Redirect::route('admin.alternatif.index')
                ->with('error', 'Tidak ada data alternatif yang dapat dihitung.');
        }

        // Normalisasi Matriks
        $matrixTernormalisasi = [];
        foreach ($namaKriteriaMap as $id => $nama) {
            $sumSquares = 0;
            foreach ($matrixKeputusan as $nilai) {
                if (isset($nilai[$nama])) {
                    $sumSquares += pow($nilai[$nama], 2);
                }
            }
            $denom = sqrt($sumSquares ?: 1);

            foreach ($matrixKeputusan as $lokasiId => $nilai) {
                $matrixTernormalisasi[$lokasiId]['alternatif'] = $nilai['alternatif'];
                $matrixTernormalisasi[$lokasiId][$nama] = isset($nilai[$nama])
                    ? round($nilai[$nama] / $denom, 3) : 0.0000;
            }
        }

        // dd($matrixKeputusan);

        // Pembobotan Matriks
        $pembobotan = [];
        foreach ($matrixTernormalisasi as $lokasiId => $nilai) {
            $pembobotan[$lokasiId]['alternatif'] = $nilai['alternatif'];
            foreach ($kriteria as $k) {
                $nama = $namaKriteriaMap[$k->id_kriteria];
                $bobot = $k->bobot_kriteria;
                $pembobotan[$lokasiId][$nama] = round(($nilai[$nama] ?? 0) * $bobot, 3);
            }
        }

        // Solusi Ideal berdasarkan tipe kriteria (benefit/cost)
        $idealPositif = [];
        $idealNegatif = [];

        foreach ($kriteria as $k) {
            $nama = $namaKriteriaMap[$k->id_kriteria];
            $values = array_column($pembobotan, $nama);

            if (strtolower($k->tipe_kriteria) === 'benefit') {
                $idealPositif[$nama] = !empty($values) ? round(max($values), 3) : 0.000;
                $idealNegatif[$nama] = !empty($values) ? round(min($values), 3) : 0.000;
            } else { // cost
                $idealPositif[$nama] = !empty($values) ? round(min($values), 3) : 0.000;
                $idealNegatif[$nama] = !empty($values) ? round(max($values), 3) : 0.000;
            }
        }


        // Jarak ke Solusi Ideal
        $dPlus = [];
        $dMin = [];
        foreach ($pembobotan as $lokasiId => $nilai) {
            $dPlus[$lokasiId] = 0;
            $dMin[$lokasiId] = 0;
            foreach ($namaKriteriaMap as $id => $nama) {
                $dPlus[$lokasiId] += pow(($nilai[$nama] ?? 0) - $idealPositif[$nama], 2);
                $dMin[$lokasiId] += pow(($nilai[$nama] ?? 0) - $idealNegatif[$nama], 2);
            }
            $dPlus[$lokasiId] = round(sqrt($dPlus[$lokasiId]), 3);
            $dMin[$lokasiId] = round(sqrt($dMin[$lokasiId]), 3);
        }

        // Preferensi
        $preferensi = [];
        foreach ($dPlus as $lokasiId => $dp) {
            $dm = $dMin[$lokasiId];
            $preferensi[$lokasiId] = round(($dp + $dm > 0 ? $dm / ($dp + $dm) : 0), 3);
        }

        // Peringkat
        arsort($preferensi);
        $peringkat = [];
        $rank = 1;
        foreach ($preferensi as $id => $val) {
            $peringkat[] = [
                'id' => $lokasiMap[$id] ?? $id,
                'nilai' => $val,
                'rank' => $rank++,
            ];
        }

        // Konversi id ke nama di preferensi dan jarak juga
        $preferensiWithName = [];
        foreach ($preferensi as $id => $val) {
            $preferensiWithName[$lokasiMap[$id] ?? $id] = $val;
        }

        $dPlusNamed = [];
        $dMinNamed = [];
        foreach ($dPlus as $id => $val) {
            $dPlusNamed[$lokasiMap[$id] ?? $id] = $val;
            $dMinNamed[$lokasiMap[$id] ?? $id] = $dMin[$id];
        }

        // Hapus semua data hasil_topsis dan reset id
        DB::table('hasil_topsis')->truncate();

        // Simpan hasil baru
        foreach ($peringkat as $entry) {
            $lokasiId = array_search($entry['id'], $lokasiMap); // dapatkan id asli dari nama
            HasilTopsis::create([
                'lokasi_wisata_id' => $lokasiId,
                'jarak_positif' => $dPlus[$lokasiId],
                'jarak_negative' => $dMin[$lokasiId],
                'tipe_preferensi' => $preferensi[$lokasiId],
                'rangking' => $entry['rank'],
            ]);
        }

        return Inertia::render('admin/Topsis/Index', [
            'matrixKeputusan' => array_values($matrixKeputusan),
            'normalisasi' => array_values($matrixTernormalisasi),
            'bobotMatriks' => array_values($pembobotan),
            'solusiIdeal' => [
                'positif' => $idealPositif,
                'negatif' => $idealNegatif,
            ],
            'jarak' => [
                'positif' => $dPlusNamed,
                'negatif' => $dMinNamed,
            ],
            'preferensi' => $preferensiWithName,
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
                'jarak_positif'    => round($item->jarak_positif, 3),
                'jarak_negatif'    => round($item->jarak_negative, 3),
                'preferensi'       => round($item->tipe_preferensi, 3),
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



        return Inertia::render('admin/Topsis/Pemetaan', [
            'lokasi' => $lokasi,
        ]);
    }
}
