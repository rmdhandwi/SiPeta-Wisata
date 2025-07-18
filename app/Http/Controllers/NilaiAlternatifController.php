<?php

namespace App\Http\Controllers;

use App\Http\Requests\admin\AlternatifRequest;
use App\Models\Kriteria;
use App\Models\LokasiWisata;
use App\Models\NilaiAlternatif;
use App\Models\Subkriteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class NilaiAlternatifController extends Controller
{

    public function index()
    {
        return Inertia::render('admin/Alternatif/Index');
    }

    public function create()
    {
        return Inertia::render('admin/Alternatif/Form');
    }


    public function store(AlternatifRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            foreach ($data['alternatif'] as $alternatif) {
                $lokasiId = $alternatif['lokasi_wisata_id'];
                $subkriteria = $alternatif['subkriteria'];

                foreach ($subkriteria as $kriteriaId => $subkriteriaId) {
                    // Ambil bobot dari subkriteria
                    $sub = Subkriteria::findOrFail($subkriteriaId);

                    NilaiAlternatif::create([
                        'lokasi_wisata_id' => $lokasiId,
                        'subkriteria_id' => $subkriteriaId,
                        'nilai' => $sub->bobot_subkriteria, // otomatis isi nilai dengan bobot
                    ]);
                }
            }

            return redirect()->route('admin.alternatif.index')
                ->with('success', 'Data alternatif berhasil disimpan.');
        } catch (\Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
        }
    }



    public function editInit(Request $request): RedirectResponse
    {
        $request->session()->put('lokasi_wisata_id', $request->id);
        return redirect()->route('admin.alternatif.edit');
    }

    public function edit(Request $request)
    {
        $id = $request->session()->pull('lokasi_wisata_id');

        if (!$id) {
            return Redirect::route('admin.alternatif.index')
                ->with('error', 'Data tidak ditemukan atau tidak valid.');
        }

        // Ambil semua nilai_alternatif terkait lokasi_wisata_id
        $nilai = NilaiAlternatif::where('lokasi_wisata_id', $id)
            ->with('subkriteria')
            ->get();

        // Ambil semua id_alternatif yang terkait
        $alternatifIds = $nilai->pluck('id_alternatif')->unique()->values();


        // Bangun subkriteriaMap
        $subkriteriaMap = [];
        foreach ($nilai as $item) {
            if ($item->subkriteria && $item->subkriteria->kriteria_id) {
                $subkriteriaMap[$item->subkriteria->kriteria_id] = $item->subkriteria_id;
            }
        }

        $alternatif = [
            [
                'lokasi_wisata_id' => $id,
                'alternatif_ids' => $alternatifIds,
                'subkriteria' => $subkriteriaMap,
            ]
        ];

        return Inertia::render('admin/Alternatif/Form', [
            'data' => [
                'alternatif' => $alternatif,
            ]
        ]);
    }

    public function update(AlternatifRequest $request, $lokasi_wisata_id): RedirectResponse
    {
        try {
            $data = $request->validated();
            $input = $data['alternatif'][0];

            $inputSubkriteriaMap = $input['subkriteria'] ?? [];
            $alternatifIds = array_values($input['alternatif_ids'] ?? []);


            // dd($input);
            if (empty($alternatifIds)) {
                return Redirect::route('admin.alternatif.index')
                    ->with('error', 'ID Alternatif tidak ditemukan.');
            }

            // Ambil hanya alternatif yang valid untuk lokasi tersebut
            $validAlternatifIds = NilaiAlternatif::where('lokasi_wisata_id', $lokasi_wisata_id)
                ->whereIn('id_alternatif', $alternatifIds)
                ->pluck('id_alternatif')
                ->toArray();

            if (count($validAlternatifIds) !== count($alternatifIds)) {
                return Redirect::route('admin.alternatif.index')
                    ->with('error', 'Beberapa ID alternatif tidak valid untuk lokasi wisata ini.');
            }

            $nilaiAlternatifList = NilaiAlternatif::whereIn('id_alternatif', $alternatifIds)
                ->with('subkriteria')
                ->get();

            if ($nilaiAlternatifList->isEmpty()) {
                return Redirect::route('admin.alternatif.index')
                    ->with('error', 'Data alternatif tidak ditemukan.');
            }

            $jumlahUpdate = 0;

            foreach ($nilaiAlternatifList as $nilai) {
                $oldSub = $nilai->subkriteria;

                if (!$oldSub || !$oldSub->kriteria_id) continue;

                $kriteriaId = $oldSub->kriteria_id;
                $newSubkriteriaId = $inputSubkriteriaMap[$kriteriaId] ?? null;

                // Abaikan jika subkriteria tidak dikirim atau tidak berubah
                if (!$newSubkriteriaId || $nilai->subkriteria_id == $newSubkriteriaId) {
                    continue;
                }

                $newSub = Subkriteria::find($newSubkriteriaId);
                if (!$newSub) continue;

                $nilai->subkriteria_id = $newSub->id_subkriteria;
                $nilai->nilai = $newSub->bobot_subkriteria;

                $nilai->save();
                $jumlahUpdate++;
            }

            $lokasi = LokasiWisata::find($lokasi_wisata_id);
            $namaLokasi = $lokasi ? $lokasi->nama_lokasi_wisata : 'lokasi tidak diketahui';

            return Redirect::route('admin.alternatif.index')
                ->with('success', $jumlahUpdate > 0
                    ? 'Berhasil memperbarui ' . $jumlahUpdate . ' data alternatif untuk lokasi ' . $namaLokasi . '.'
                    : 'Tidak ada perubahan yang disimpan untuk lokasi ' . $namaLokasi . '.');
        } catch (\Throwable $e) {
            return Redirect::route('admin.alternatif.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui data alternatif.' .  $e->getMessage());
        }
    }

    public function destroy($lokasi_wisata_id): RedirectResponse
    {
        try {
            $deleted = NilaiAlternatif::where('lokasi_wisata_id', $lokasi_wisata_id)->delete();

            if ($deleted > 0) {
                return redirect()->back()->with('success', "Berhasil menghapus $deleted data alternatif untuk lokasi tersebut.");
            } else {
                return redirect()->back()->with('error', 'Tidak ada data alternatif yang ditemukan untuk lokasi ini.');
            }
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
