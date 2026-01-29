<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            // User basic info
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],

            // Profile fields
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'identity_number' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('user_profiles')->ignore($this->user()->profile->id ?? null)
            ],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'emergency_contact' => ['nullable', 'string', 'max:20'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],

            // Documents
            'ktp_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,pdf', 'max:2048'],
            'passport_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,pdf', 'max:2048'],
            'sim_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,pdf', 'max:2048'],
            'other_document' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:2048'],

            // Delete flags
            'delete_ktp' => ['nullable', 'boolean'],
            'delete_passport' => ['nullable', 'boolean'],
            'delete_sim' => ['nullable', 'boolean'],
            'delete_other' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah digunakan',
            'email.email' => 'Format email tidak valid',

            'phone.max' => 'Nomor telepon maksimal 20 karakter',
            'identity_number.max' => 'Nomor identitas maksimal 50 karakter',
            'identity_number.unique' => 'Nomor identitas sudah terdaftar',
            'date_of_birth.date' => 'Format tanggal lahir tidak valid',
            'date_of_birth.before' => 'Tanggal lahir tidak valid',
            'occupation.max' => 'Pekerjaan maksimal 100 karakter',

            'ktp_photo.image' => 'File KTP harus berupa gambar',
            'ktp_photo.mimes' => 'Format file KTP harus JPG, PNG, atau PDF',
            'ktp_photo.max' => 'Ukuran file KTP maksimal 2MB',

            'passport_photo.image' => 'File Passport harus berupa gambar',
            'passport_photo.mimes' => 'Format file Passport harus JPG, PNG, atau PDF',
            'passport_photo.max' => 'Ukuran file Passport maksimal 2MB',

            'sim_photo.image' => 'File SIM harus berupa gambar',
            'sim_photo.mimes' => 'Format file SIM harus JPG, PNG, atau PDF',
            'sim_photo.max' => 'Ukuran file SIM maksimal 2MB',

            'other_document.file' => 'File dokumen tidak valid',
            'other_document.mimes' => 'Format file harus JPG, PNG, atau PDF',
            'other_document.max' => 'Ukuran file maksimal 2MB',
        ];
    }
}
