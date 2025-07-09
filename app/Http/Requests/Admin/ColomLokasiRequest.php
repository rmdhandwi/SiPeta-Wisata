<?php

namespace App\Http\Requests\admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ColomLokasiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_colom' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_colom.required' => 'Nama kolom wajib diisi.',
            'nama_colom.regex' => 'Nama kolom hanya boleh berisi huruf dan spasi.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $rawName = $this->input('nama_colom');
            $normalized = Str::slug($rawName, '_'); // ubah ke snake_case

            if (Schema::hasColumn('lokasi_wisata', $normalized)) {
                $validator->errors()->add('nama_colom', "Kolom '$normalized' sudah ada.");
            }
        });
    }
}
