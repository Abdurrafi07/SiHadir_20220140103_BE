<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam username string required Username unik hanya huruf. Contoh: johndoe
 * @bodyParam email string required Alamat email yang unik. Contoh: john@example.com
 * @bodyParam password string required Kata sandi minimal 6 karakter. Contoh: rahasia123
 * @bodyParam role_id integer required ID dari role yang tersedia. Contoh: 2
 * @bodyParam id_kelas integer required ID kelas yang tersedia. Contoh: 1
 */
class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255|regex:/^[a-zA-Z0-9_]+$/|unique:users,name',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6',
            // 'role_id'   => 'required|exists:roles,id',
            // 'id_kelas'  => 'required|exists:kelas,id',
        ];
    }
}
