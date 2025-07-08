<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam nama_mapel string required Nama mata pelajaran. Contoh: Matematika
 * @bodyParam kelas_id array optional Array ID kelas yang ditautkan. Contoh: [1, 2]
 */
class StoreMapelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_mapel' => 'required|string|max:255|unique:mapel,nama_mapel',
            'kelas_id' => 'nullable|array',
            'kelas_id.*' => 'exists:kelas,id',
        ];
    }
}
