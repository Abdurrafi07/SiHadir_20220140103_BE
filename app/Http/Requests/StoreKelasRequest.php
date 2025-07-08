<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam nama_kelas string required Nama kelas. Contoh: Kelas 1A
 */
class StoreKelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas',
        ];
    }
}
