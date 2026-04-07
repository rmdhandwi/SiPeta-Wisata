<?php

namespace App\Http\Controllers;

use App\Http\Requests\admin\ColomLokasiRequest;
use App\Http\Requests\Admin\LokasiWisataRequest;
use App\Models\Kriteria;
use App\Models\LokasiWisata;
use App\Models\NilaiAlternatif;
use App\Models\Subkriteria;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class LokasiWisataController extends Controller
{

    public function index()
    {
        return Inertia::render('admin/Lokasi/Index');
    }


    public function create()
    {
        $fields = (new LokasiWisata())->getFillable();
        $kriteria = Kriteria::with('subkriteria')->get();

        return Inertia::render('admin/Lokasi/Form', [
            'fields' => $fields,
            'kriteriaOptions' => $kriteria,
        ]);
    }


    public function store(LokasiWisataRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // implode array
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = implode(',', $value);
                }
            }

            // simpan lokasi
            $lokasi = LokasiWisata::create($data);

            // 🔥 simpan nilai langsung
            foreach (
                $request->except([
                    '_token',
                    '_method',
                    'nama_lokasi_wisata',
                    'jenis_wisata_id',
                    'longitude',
                    'latitude'
                ]) as $field => $value
            ) {

                if (!$value) continue;

                $ids = is_array($value) ? $value : [$value];

                $subs = Subkriteria::whereIn('id_subkriteria', $ids)->get();

                foreach ($subs as $sub) {
                    NilaiAlternatif::create([
                        'lokasi_wisata_id' => $lokasi->id_lokasi_wisata ?? $lokasi->id_lokasi_wisata,
                        'subkriteria_id' => $sub->id_subkriteria,
                        'nilai' => $sub->bobot_subkriteria,
                    ]);
                }
            }

            DB::commit();

            return back()->with('success', 'Data berhasil ditambahkan');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan data');
        }
    }

    public function colom(ColomLokasiRequest $request): RedirectResponse
    {
        try {
            // Ambil dan normalisasi nama kolom dari input
            $rawName = $request->input('nama_colom');
            $normalized = Str::slug($rawName, '_'); // ubah spasi jadi underscore & huruf kecil

            // Tambahkan kolom ke tabel lokasi_wisata
            Schema::table('lokasi_wisata', function (Blueprint $table) use ($normalized) {
                $table->string($normalized)->nullable()->after('akses_lokasi'); // kolom baru bertipe string dan nullable
            });

            return redirect()
                ->route('admin.lokasiwisata.index')
                ->with('success', "Kolom '$normalized' berhasil ditambahkan.");
        } catch (Exception $e) {
            return redirect()
                ->route('admin.lokasiwisata.index')
                ->with('error', 'Gagal menambahkan kolom. Silakan coba lagi.');
        }
    }


    public function editInit(Request $request): RedirectResponse
    {
        // dd($request->id);
        $request->session()->put('edit_lokasi_wisata_id',  $request->id);
        return redirect()->route('admin.lokasiwisata.edit');
    }


    public function edit(Request $request)
    {
        $id = $request->session()->pull('edit_lokasi_wisata_id');

        if (!$id) {
            return redirect()->route('admin.lokasiwisata.index')
                ->with('error', 'Data tidak ditemukan.');
        }

        $data = LokasiWisata::findOrFail($id);

        // 🔥 FIX MULTISELECT (WAJIB)
        $data->transportasi = $data->transportasi
            ? array_map('intval', explode(',', $data->transportasi))
            : [];

        $data->fasilitas = $data->fasilitas
            ? array_map('intval', explode(',', $data->fasilitas))
            : [];

        // 🔥 convert field lain otomatis
        foreach ($data->getAttributes() as $key => $value) {

            if (in_array($key, ['latitude', 'longitude', 'transportasi', 'fasilitas'])) {
                continue;
            }

            if (is_string($value) && str_contains($value, ',')) {
                $data->$key = array_map('intval', explode(',', $value));
            } elseif (is_numeric($value)) {
                $data->$key = (int) $value;
            }
        }

        $kriteria = Kriteria::with('subkriteria')->get();
        $fields = (new LokasiWisata())->getFillable();

        return Inertia::render('admin/Lokasi/Form', [
            'data' => $data,
            'fields' => $fields,
            'kriteriaOptions' => $kriteria,
        ]);
    }


    public function update(LokasiWisataRequest $request, LokasiWisata $lokasiwisata)
    {
        DB::beginTransaction();

        try {
            // ===============================
            // 1. UPDATE DATA LOKASI
            // ===============================
            $data = collect($request->validated())
                ->map(fn($v) => is_array($v) ? implode(',', $v) : $v)
                ->toArray();

            $lokasiwisata->update($data);

            // ===============================
            // 2. SETUP
            // ===============================
            $jenisWisataId = Kriteria::where('nama_kriteria', 'Jenis wisata')
                ->value('id_kriteria');

            $subMap = Subkriteria::pluck('kriteria_id', 'id_subkriteria');

            // ===============================
            // 3. AMBIL INPUT (GROUP PER FIELD)
            // ===============================
            $inputs = collect($request->except([
                '_token',
                '_method',
                'nama_lokasi_wisata',
                'jenis_wisata_id',
                'longitude',
                'latitude'
            ]));

            foreach ($inputs as $field => $val) {

                // ubah ke array (biar support multi)
                $ids = collect(is_array($val) ? $val : [$val])
                    ->filter()
                    ->map(fn($id) => (int) $id)
                    ->unique()
                    ->values();

                if ($ids->isEmpty()) continue;

                // ambil kriteria dari sub pertama
                $kriteriaId = $subMap[$ids->first()] ?? null;

                // skip jenis wisata
                if ($kriteriaId == $jenisWisataId) continue;

                // ===============================
                // 🔥 DELETE SEMUA DATA LAMA PER KRITERIA
                // ===============================
                NilaiAlternatif::where('lokasi_wisata_id', $lokasiwisata->id_lokasi_wisata)
                    ->whereIn('subkriteria_id', function ($q) use ($kriteriaId) {
                        $q->select('id_subkriteria')
                            ->from('subkriteria')
                            ->where('kriteria_id', $kriteriaId);
                    })
                    ->delete();

                // ===============================
                // 🔥 INSERT ULANG (MULTI SUPPORT)
                // ===============================
                $subs = Subkriteria::whereIn('id_subkriteria', $ids)->get();

                foreach ($subs as $sub) {
                    NilaiAlternatif::create([
                        'lokasi_wisata_id' => $lokasiwisata->id_lokasi_wisata,
                        'subkriteria_id' => $sub->id_subkriteria,
                        'nilai' => $sub->bobot_subkriteria,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.lokasiwisata.index')
                ->with('success', 'Data berhasil diperbarui');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.lokasiwisata.index')
                ->with('error', 'Gagal memperbarui data');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $lokasi = LokasiWisata::findOrFail($id);

            $alternatif = NilaiAlternatif::where('lokasi_wisata_id', $lokasi->id_lokasi_wisata)->first();

            if ($alternatif) {
                NilaiAlternatif::where('id_alternatif', $alternatif->id_alternatif)->delete();
                $alternatif->delete();
            }

            $lokasi->delete();

            DB::commit();

            return redirect()->route('admin.lokasiwisata.index')
                ->with('success', 'Data berhasil dihapus');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.lokasiwisata.index')
                ->with('error', 'Gagal menghapus data');
        }
    }
}
