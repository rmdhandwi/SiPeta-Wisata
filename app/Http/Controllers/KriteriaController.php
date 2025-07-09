<?php

namespace App\Http\Controllers;

use App\Http\Requests\admin\KriteriaRequest;
use App\Models\Kriteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class KriteriaController extends Controller
{

    public function index()
    {
        return Inertia::render('admin/Kriteria/Index');
    }


    public function create()
    {
        return Inertia::render('admin/Kriteria/Form');
    }


    public function store(KriteriaRequest $request): RedirectResponse
    {
        try {
            Kriteria::create($request->validated());

            return redirect()->back()->with('success', 'Data kriteria berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data kriteria. Silakan coba lagi.');
        }
    }


    public function editInit(Request $request): RedirectResponse
    {
        // dd($request->id);
        $request->session()->put('edit_kriteria_id',  $request->id);
        return redirect()->route('admin.kriteria.edit');
    }


    public function edit(Request $request)
    {
        // Ambil ID dari session
        $id = $request->session()->pull('edit_kriteria_id');

        // Jika tidak ada ID, redirect dengan flash message error
        if (!$id) {
            return Redirect::route('admin.kriteria.index')
                ->with('error', 'Data tidak ditemukan atau tidak valid.');
        }

        // Ambil data berdasarkan ID
        $data = Kriteria::find($id);

        if (!$data) {
            return Redirect::route('admin.kriteria.index')
                ->with('error', 'Data kriteria tidak ditemukan.');
        }

        return Inertia::render('admin/Kriteria/Form', [
            'data' => $data,
        ]);
    }


    public function update(KriteriaRequest $request, Kriteria $Kriteria): RedirectResponse
    {
        try {
            $Kriteria->update($request->validated());

            return Redirect::route('admin.kriteria.index')
                ->with('success', 'Data kriteria berhasil diperbarui.');
        } catch (\Exception $e) {
            return Redirect::route('admin.kriteria.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }


    public function destroy($id): RedirectResponse
    {
        try {
            // Cari data kriteria, jika tidak ditemukan akan throw ModelNotFoundException
            $Kriteria = Kriteria::findOrFail($id);
            $Kriteria->delete();

            return redirect()->route('admin.kriteria.index')
                ->with('success', 'Data kriteria berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.kriteria.index')
                ->with('error', 'Data kriteria tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.kriteria.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
