<?php

namespace App\Http\Requests\Admin;

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

        foreach ($fillable as $field) {
            if ($field === 'id_lokasi_wisata') continue;

            $rules[$field] = match (true) {
                $field === 'jenis_wisata_id' => ['required', 'exists:jenis_wisata,id_jenis_wisata'],
                $field === 'nama_lokasi_wisata' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('lokasi_wisata', 'nama_lokasi_wisata')->ignore($this->id_lokasi_wisata, 'id_lokasi_wisata'),
                ],
                in_array($field, ['longitude', 'latitude']) => ['required', 'numeric'],
                default => ['required', 'string'],
            };
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [];

        $model = new LokasiWisata();
        $fillable = $model->getFillable();

        foreach ($fillable as $field) {
            if ($field === 'id_lokasi_wisata') continue;

            $label = ucfirst(str_replace('_', ' ', $field));

            $messages["$field.required"] = "$label wajib diisi.";

            if ($field === 'nama_lokasi_wisata') {
                $messages["$field.unique"] = "$label sudah digunakan.";
                $messages["$field.max"] = "$label terlalu panjang.";
            }

            if (in_array($field, ['longitude', 'latitude'])) {
                $messages["$field.numeric"] = "$label harus berupa angka.";
            }

            if ($field === 'jenis_wisata_id') {
                $messages["$field.exists"] = "$label tidak valid.";
            }
        }

        return $messages;
    }
}
