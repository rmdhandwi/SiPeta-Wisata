<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\JenisWisataRequest;
use App\Models\JenisWisata;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class JenisWisataController extends Controller
{
    // memanggil tampilan jenis wisata
    public function index()
    {
        return Inertia::render('admin/JenisWisata/Index');
    }


    // memanggil tampilan tambah data jenis wisata
    public function create(): Response
    {
        return Inertia::render('admin/JenisWisata/Form');
    }

    
    public function store(JenisWisataRequest $request): RedirectResponse
    {
        try {
            JenisWisata::create($request->validated());

            return redirect()->back()->with('success', 'Data jenis wisata berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Tangani error tak terduga dan beri feedback ke user
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function editInit(Request $request): RedirectResponse
    {
        $id = $request->id;

        if (!$id || !is_numeric($id)) {
            return redirect()->route('admin.jeniswisata.index')
                ->with('error', 'ID tidak valid.');
        }

        // Simpan ID ke dalam session
        $request->session()->put('edit_jenis_wisata_id', $id);

        // Redirect ke halaman edit
        return redirect()->route('admin.jeniswisata.edit');
    }

   
    public function edit(Request $request)
    {
        // Ambil ID dari session
        $id = $request->session()->pull('edit_jenis_wisata_id');

        // Jika tidak ada ID, redirect dengan flash message error
        if (!$id) {
            return Redirect::route('admin.jeniswisata.index')
                ->with('error', 'ID jenis wisata tidak ditemukan atau tidak valid.');
        }

        // Ambil data berdasarkan ID
        $data = JenisWisata::find($id);

        if (!$data) {
            return Redirect::route('admin.jeniswisata.index')
                ->with('error', 'Data jenis wisata tidak ditemukan.');
        }

        return Inertia::render('admin/JenisWisata/Form', [
            'data' => $data,
        ]);
    }



    public function update(JenisWisataRequest $request, JenisWisata $jenisWisata): RedirectResponse
    {
        try {
            $jenisWisata->update($request->validated());

            return Redirect::route('admin.jeniswisata.index')
                ->with('success', 'Data jenis wisata berhasil diperbarui.');
        } catch (\Exception $e) {
            return Redirect::route('admin.jeniswisata.index')
                ->with('error', 'Gagal memperbarui data jenis wisata: ' . $e->getMessage());
        }
    }


    public function destroy($id): RedirectResponse
    {
        try {
            $jenisWisata = JenisWisata::findOrFail($id);
            $jenisWisata->delete();

            return redirect()->route('admin.jeniswisata.index')
                ->with('success', 'Data jenis berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.jeniswisata.index')
                ->with('error', 'Data tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.jeniswisata.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
