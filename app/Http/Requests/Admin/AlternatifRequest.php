<?php

namespace App\Http\Requests\admin;

use App\Models\Kriteria;
use App\Models\NilaiAlternatif;
use Illuminate\Foundation\Http\FormRequest;

class AlternatifRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function isEdit(): bool
    {
        return $this->route('lokasi_wisata_id') !== null;
    }

    public function rules(): array
    {
        $rules = [
            'alternatif' => ['required', 'array', 'min:1'],

            'alternatif.*.lokasi_wisata_id' => array_filter([
                'required',
                'exists:lokasi_wisata,id_lokasi_wisata',
                $this->isEdit() ? null : 'distinct',
            ]),

            'alternatif.*.subkriteria' => ['required', 'array'],

            'alternatif.*.alternatif_ids' => [$this->isEdit() ? 'required' : 'nullable', 'array'],
            'alternatif.*.alternatif_ids.*' => ['integer'],
        ];

        // Tambahkan validasi subkriteria per kriteria
        $kriteria = Kriteria::pluck('id_kriteria');
        foreach ($kriteria as $id) {
            $rules["alternatif.*.subkriteria.$id"] = [
                'required',
                'exists:subkriteria,id_subkriteria',
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [
            'alternatif.required' => 'Minimal satu data alternatif harus diisi.',

            'alternatif.*.lokasi_wisata_id.required' => 'Lokasi wisata wajib diisi.',
            'alternatif.*.lokasi_wisata_id.exists' => 'Lokasi wisata tidak valid.',
            'alternatif.*.lokasi_wisata_id.distinct' => 'Lokasi wisata tidak boleh duplikat.',

            'alternatif.*.subkriteria.required' => 'Semua subkriteria wajib diisi.',

            'alternatif.*.alternatif_ids.required' => 'ID alternatif wajib saat melakukan update.',
            'alternatif.*.alternatif_ids.array' => 'ID alternatif harus berupa array.',
            'alternatif.*.alternatif_ids.*.integer' => 'Setiap ID dalam alternatif harus berupa angka.',
        ];

        // Tambahkan pesan khusus per kriteria
        $kriteria = Kriteria::pluck('nama_kriteria', 'id_kriteria');
        foreach ($kriteria as $id => $nama) {
            $messages["alternatif.*.subkriteria.$id.required"] = "Subkriteria untuk kriteria '$nama' wajib dipilih.";
            $messages["alternatif.*.subkriteria.$id.exists"] = "Subkriteria untuk kriteria '$nama' tidak valid.";
        }

        return $messages;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->isEdit()) {
                return; // skip untuk edit
            }

            $seen = [];

            foreach ($this->input('alternatif', []) as $index => $alt) {
                $lokasi = $alt['lokasi_wisata_id'] ?? null;
                $subs = $alt['subkriteria'] ?? [];

                foreach ($subs as $kriteria_id => $sub_id) {
                    $key = "$lokasi-$kriteria_id";

                    // Duplikat dalam input
                    if (isset($seen[$key])) {
                        $validator->errors()->add(
                            "alternatif.$index.subkriteria.$kriteria_id",
                            "Subkriteria untuk kriteria ini sudah dipilih dua kali untuk lokasi yang sama."
                        );
                    } else {
                        $seen[$key] = true;
                    }

                    // Duplikat dalam database
                    $alreadyExists = NilaiAlternatif::where('lokasi_wisata_id', $lokasi)
                        ->where('subkriteria_id', $sub_id)
                        ->exists();

                    if ($alreadyExists) {
                        $validator->errors()->add(
                            "alternatif.$index.subkriteria.$kriteria_id",
                            "Data nilai alternatif untuk lokasi dan subkriteria ini sudah ada di database."
                        );
                    }
                }
            }
        });
    }
}
