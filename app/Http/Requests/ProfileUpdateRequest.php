<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],

            // Profile fields - semua optional
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'identity_number' => ['nullable', 'string', 'max:16'],
            'date_of_birth' => ['nullable', 'date'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'emergency_contact' => ['nullable', 'string', 'max:20'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female'],

            // Document uploads - PENTING: max 2MB (2048 KB)
            'ktp_photo' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:2048'],
            'passport_photo' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:2048'],
            'sim_photo' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:2048'],

            // Delete flags
            'delete_ktp' => ['nullable', 'boolean'],
            'delete_passport' => ['nullable', 'boolean'],
            'delete_sim' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi',
            'ktp_photo.max' => 'Ukuran file KTP maksimal 2MB',
            'ktp_photo.mimes' => 'Format file KTP harus: JPG, PNG, atau PDF',
            'passport_photo.max' => 'Ukuran file Passport maksimal 2MB',
            'passport_photo.mimes' => 'Format file Passport harus: JPG, PNG, atau PDF',
            'sim_photo.max' => 'Ukuran file SIM maksimal 2MB',
            'sim_photo.mimes' => 'Format file SIM harus: JPG, PNG, atau PDF',
            'identity_number.max' => 'Nomor KTP maksimal 16 digit',
        ];
    }
}
