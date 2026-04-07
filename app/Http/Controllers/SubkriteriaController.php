<?php

namespace App\Http\Controllers;

use App\Http\Requests\admin\SubkriteriaRequest;
use App\Models\Subkriteria;
use App\Models\Subsubkriteria;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
            Subkriteria::create([
                'kriteria_id' => $request->kriteria_id,
                'nama_subkriteria' => trim($request->nama_subkriteria),
                'bobot_subkriteria' => $request->bobot_subkriteria,
            ]);

            return redirect()->route('admin.subkriteria.index')
                ->with('success', 'Data subkriteria berhasil ditambahkan.');
        } catch (Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan data.');
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


    public function update(SubkriteriaRequest $request, Subkriteria $subkriteria): RedirectResponse
    {
        try {
            $subkriteria->update([
                'kriteria_id' => $request->kriteria_id,
                'nama_subkriteria' => trim($request->nama_subkriteria),
                'bobot_subkriteria' => $request->bobot_subkriteria,
            ]);

            return redirect()->route('admin.subkriteria.index')
                ->with('success', 'Data subkriteria berhasil diperbarui.');
        } catch (Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal update data.');
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
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.subkriteria.index')
                ->with('error', 'Data subkriteria tidak ditemukan.');
        } catch (Exception $e) {
            return redirect()->route('admin.subkriteria.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
