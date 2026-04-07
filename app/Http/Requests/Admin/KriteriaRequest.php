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
        $kriteria = $this->route('kriteria');
        $id = is_object($kriteria) ? $kriteria->id_kriteria : $kriteria;

        return [
            'nama_kriteria' => [
                'required',
                'string',
                Rule::unique('kriteria', 'nama_kriteria')->ignore($id, 'id_kriteria'),
            ],

            'bobot_kriteria' => [
                'required',
                'numeric',
                'min:1',
                'max:100',
            ],

            'tipe_kriteria' => [
                'required',
                Rule::in(['Benefit', 'Cost']),
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $kriteria = $this->route('kriteria');
            $id = is_object($kriteria) ? $kriteria->id_kriteria : $kriteria;

            $bobotLain = Kriteria::when($id, function ($query) use ($id) {
                return $query->where('id_kriteria', '!=', $id);
            })->sum('bobot_kriteria');

            $inputBobot = (float) $this->input('bobot_kriteria');
            $total = $bobotLain + $inputBobot;

            // 🔥 wajib = 100
            if ($total != 100) {
                $validator->errors()->add(
                    'bobot_kriteria',
                    'Total bobot semua kriteria harus = 100 (sekarang: ' . $total . ')'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'nama_kriteria.required' => 'Nama kriteria wajib diisi.',
            'nama_kriteria.string' => 'Nama kriteria harus berupa teks.',
            'nama_kriteria.unique' => 'Nama kriteria sudah digunakan.',

            'bobot_kriteria.required' => 'Bobot kriteria wajib diisi.',
            'bobot_kriteria.numeric' => 'Bobot kriteria harus berupa angka.',
            'bobot_kriteria.min' => 'Bobot minimal 1.',
            'bobot_kriteria.max' => 'Bobot maksimal 100.',

            'tipe_kriteria.required' => 'Tipe kriteria wajib diisi.',
            'tipe_kriteria.in' => 'Tipe kriteria harus Benefit atau Cost.',
        ];
    }
}
