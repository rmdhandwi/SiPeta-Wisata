<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisWisataRequest extends FormRequest
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
        $jenisWisataId = $this->route('jenisWisata'); // Ambil ID dari route

        return [
            'nama_jenis_wisata' => [
                'required',
                'string',
                Rule::unique('jenis_wisata', 'nama_jenis_wisata')->ignore($jenisWisataId),
            ],
            'keterangan' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_jenis_wisata.required' => 'Nama jenis wisata wajib diisi.',
            'nama_jenis_wisata.string' => 'Nama jenis wisata harus berupa teks.',
            'nama_jenis_wisata.unique' => 'Nama jenis wisata sudah digunakan.',

            'keterangan.required' => 'Keterangan wajib diisi.',
            'keterangan.string' => 'Keterangan harus berupa teks.',
        ];
    }
}
