<?php

namespace App\Http\Requests\admin;

use App\Models\Kriteria;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KriteriaRequest extends FormRequest
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
        $id = $this->route('kriteria'); // akan null saat create

        return [
            'nama_kriteria' => [
                'required',
                'string',
                Rule::unique('kriteria', 'nama_kriteria')->ignore($id, 'id_kriteria'),
            ],
            'bobot_kriteria' => [
                'required',
                'numeric',
                'between:1,5'
                // 'between:1,100',
                // function ($attribute, $value, $fail) use ($id) {
                //     $bobotLain = Kriteria::when($id, function ($query) use ($id) {
                //         return $query->where('id_kriteria', '!=', $id);
                //     })->sum('bobot_kriteria');

                //     $totalBobot = $bobotLain + (float) $value;

                //     if ($totalBobot > 100) {
                //         $fail("Total bobot melebihi 100. Sisa bobot tersedia: " . (100 - $bobotLain));
                //     }
                // }
            ],
            'tipe_kriteria' => ['required', Rule::in(['Benefit', 'Cost'])],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kriteria.required' => 'Nama kriteria wajib diisi.',
            'nama_kriteria.unique' => 'Nama kriteria sudah digunakan.',
            'bobot_kriteria.required' => 'Bobot wajib diisi.',
            'bobot_kriteria.numeric' => 'Bobot harus berupa angka.',
            'bobot_kriteria.between' => 'Bobot harus antara 1 sampai 5.',
            // 'bobot_kriteria.between' => 'Bobot harus antara 1 sampai 100.',
            'tipe_kriteria.required' => 'Tipe kriteria wajib dipilih.',
            'tipe_kriteria.in' => 'Tipe kriteria harus berupa Benefit atau Cost.',
        ];
    }
}
