<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',

            // Profile fields
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'identity_number' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date|before:today',
            'occupation' => 'nullable|string|max:100',
            'emergency_contact' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:255',
            'gender' => ['nullable', Rule::in(['male', 'female'])],

            // Documents
            'ktp_photo' => 'nullable|image|mimes:jpeg,jpg,png,pdf|max:2048',
            'passport_photo' => 'nullable|image|mimes:jpeg,jpg,png,pdf|max:2048',
            'sim_photo' => 'nullable|image|mimes:jpeg,jpg,png,pdf|max:2048',
            'other_document' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role_id.required' => 'Role wajib dipilih',
            'role_id.exists' => 'Role tidak valid',
            'ktp_photo.image' => 'File KTP harus berupa gambar',
            'ktp_photo.max' => 'Ukuran file KTP maksimal 2MB',
            'passport_photo.image' => 'File Passport harus berupa gambar',
            'passport_photo.max' => 'Ukuran file Passport maksimal 2MB',
            'sim_photo.image' => 'File SIM harus berupa gambar',
            'sim_photo.max' => 'Ukuran file SIM maksimal 2MB',
            'other_document.max' => 'Ukuran file maksimal 2MB',
        ];
    }
}
