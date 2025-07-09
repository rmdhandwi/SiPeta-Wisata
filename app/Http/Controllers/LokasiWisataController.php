<?php

namespace App\Http\Controllers;

use App\Http\Requests\admin\ColomLokasiRequest;
use App\Http\Requests\Admin\LokasiWisataRequest;
use App\Models\LokasiWisata;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
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

        return Inertia::render('admin/Lokasi/Form', [
            'fields' => $fields,
        ]);
    }



    public function store(LokasiWisataRequest $request): RedirectResponse
    {
        try {
            LokasiWisata::create($request->validated());

            return redirect()->back()->with('success', 'Data lokasi wisata berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data lokasi. Silakan coba lagi.');
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
        } catch (\Exception $e) {
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
        // Ambil ID dari session
        $id = $request->session()->pull('edit_lokasi_wisata_id');

        // Jika tidak ada ID, redirect dengan flash message error
        if (!$id) {
            return Redirect::route('admin.lokasiwisata.index')
                ->with('error', 'Data tidak ditemukan atau tidak valid.');
        }

        // Ambil data berdasarkan ID
        $data = LokasiWisata::find($id);

        if (!$data) {
            return Redirect::route('admin.lokasiwisata.index')
                ->with('error', 'Data lokasi wisata tidak ditemukan.');
        }

        $fields = (new LokasiWisata())->getFillable();

        return Inertia::render('admin/Lokasi/Form', [
            'data' => $data,
            'fields' => $fields,
        ]);
    }


    public function update(LokasiWisataRequest $request, LokasiWisata $lokasiwisata): RedirectResponse
    {
        try {
            $lokasiwisata->update($request->validated());

            return Redirect::route('admin.lokasiwisata.index')
                ->with('success', 'Data lokasi wisata berhasil diperbarui.');
        } catch (\Exception $e) {
            return Redirect::route('admin.lokasiwisata.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }


    public function destroy($id): RedirectResponse
    {
        try {
            // Cari data lokasi wisata, jika tidak ditemukan akan throw ModelNotFoundException
            $lokasiWisata = LokasiWisata::findOrFail($id);
            $lokasiWisata->delete();

            return redirect()->route('admin.lokasiwisata.index')
                ->with('success', 'Data lokasi wisata berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.lokasiwisata.index')
                ->with('error', 'Data lokasi wisata tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.lokasiwisata.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
