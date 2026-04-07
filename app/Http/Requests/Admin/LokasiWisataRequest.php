<?php

namespace App\Http\Requests\Admin;

use App\Models\Kriteria;
use App\Models\LokasiWisata;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LokasiWisataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [];

        $model = new LokasiWisata();
        $fillable = $model->getFillable();

        // 🔥 ambil semua nama kriteria yang sudah dinormalisasi
        $kriteriaFields = Kriteria::pluck('nama_kriteria')
            ->map(fn($k) => strtolower(str_replace(' ', '_', $k)))
            ->toArray();

        foreach ($fillable as $field) {
            if ($field === 'id_lokasi_wisata') continue;

            $rules[$field] = match (true) {

                $field === 'jenis_wisata_id' => ['required', 'exists:jenis_wisata,id_jenis_wisata'],

                $field === 'nama_lokasi_wisata' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('lokasi_wisata', 'nama_lokasi_wisata')
                        ->ignore($this->id_lokasi_wisata, 'id_lokasi_wisata'),
                ],

                in_array($field, ['fasilitas', 'transportasi']) => ['required', 'array'],

                in_array($field, $kriteriaFields) => ['required', 'exists:subkriteria,id_subkriteria'],

                in_array($field, ['longitude', 'latitude']) => ['required', 'numeric'],

                default => ['nullable'],
            };
        }

        $rules['fasilitas.*'] = ['exists:subkriteria,id_subkriteria'];
        $rules['transportasi.*'] = ['exists:subkriteria,id_subkriteria'];

        return $rules;
    }

    public function messages(): array
    {
        $messages = [];

        $model = new LokasiWisata();
        $fillable = $model->getFillable();

        foreach ($fillable as $field) {
            if ($field === 'id_lokasi_wisata') continue;

            $label = ucwords(str_replace('_', ' ', $field));

            $messages["$field.required"] = "$label wajib diisi.";
            $messages["$field.string"] = "$label harus berupa teks.";
            $messages["$field.numeric"] = "$label harus berupa angka.";
            $messages["$field.array"] = "$label harus berupa pilihan.";
            $messages["$field.exists"] = "$label tidak valid.";

            if ($field === 'nama_lokasi_wisata') {
                $messages["$field.unique"] = "$label sudah digunakan.";
                $messages["$field.max"] = "$label maksimal 255 karakter.";
            }
        }

        $messages['fasilitas.*.exists'] = 'Fasilitas tidak valid.';
        $messages['transportasi.*.exists'] = 'Transportasi tidak valid.';

        return $messages;
    }
}
