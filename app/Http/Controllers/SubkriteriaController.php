<?php

namespace App\Http\Controllers;

use App\Http\Requests\admin\SubkriteriaRequest;
use App\Models\Subkriteria;
use App\Models\Subsubkriteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SubkriteriaController extends Controller
{

    public function index()
    {
        return Inertia::render('admin/Subkriteria/Index');
    }


    public function create()
    {
        return Inertia::render('admin/Subkriteria/Form');
    }


    public function store(SubkriteriaRequest $request): RedirectResponse
    {
        try {
            Subkriteria::create($request->validated());

            return redirect()->back()->with('success', 'Data subkriteria berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data subkriteria. Silakan coba lagi.');
        }
    }


    public function editInit(Request $request): RedirectResponse
    {
        // dd($request->id);
        $request->session()->put('edit_subkriteria_id',  $request->id);
        return redirect()->route('admin.subkriteria.edit');
    }


    public function edit(Request $request)
    {
        // Ambil ID dari session
        $id = $request->session()->pull('edit_subkriteria_id');

        // Jika tidak ada ID, redirect dengan flash message error
        if (!$id) {
            return Redirect::route('admin.subkriteria.index')
                ->with('error', 'Data tidak ditemukan atau tidak valid.');
        }

        // Ambil data berdasarkan ID
        $data = Subkriteria::find($id);

        if (!$data) {
            return Redirect::route('admin.subkriteria.index')
                ->with('error', 'Data subkriteria tidak ditemukan.');
        }

        return Inertia::render('admin/Subkriteria/Form', [
            'data' => $data,
        ]);
    }


    public function update(SubkriteriaRequest $request, subkriteria $subkriteria): RedirectResponse
    {
        try {
            $subkriteria->update($request->validated());

            return Redirect::route('admin.subkriteria.index')
                ->with('success', 'Data subkriteria berhasil diperbarui.');
        } catch (\Exception $e) {
            return Redirect::route('admin.subkriteria.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }


    public function destroy($id): RedirectResponse
    {
        try {
            // Cari data subkriteria, jika tidak ditemukan akan throw ModelNotFoundException
            $subkriteria = Subkriteria::findOrFail($id);
            $subkriteria->delete();

            return redirect()->route('admin.subkriteria.index')
                ->with('success', 'Data subkriteria berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.subkriteria.index')
                ->with('error', 'Data subkriteria tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.subkriteria.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
