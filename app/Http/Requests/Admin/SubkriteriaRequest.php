<?php

namespace App\Http\Requests\admin;

use App\Models\Subkriteria;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubkriteriaRequest extends FormRequest
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
            'kriteria_id' => ['required', 'exists:kriteria,id_kriteria'],
            'nama_subkriteria' => [
                'required',
                'string',
                Rule::unique('subkriteria', 'nama_subkriteria')->ignore($this->id_subkriteria, 'id_subkriteria'),
            ],
            'bobot_subkriteria' => [
                'required',
                'numeric',
                'between:1,5',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'kriteria_id.required' => 'Kriteria wajib dipilih.',
            'kriteria_id.exists' => 'Kriteria tidak valid.',
            'nama_subkriteria.required' => 'Nama kriteria wajib diisi.',
            'nama_subkriteria.unique' => 'Nama kriteria sudah digunakan.',
            'bobot_subkriteria.required' => 'Bobot wajib diisi.',
            'bobot_subkriteria.numeric' => 'Bobot harus berupa angka.',
            'bobot_subkriteria.between' => 'Bobot harus antara 1 sampai 5.',
        ];
    }
}
