<?php
// app/Http/Requests/SettingRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:50'],
            'company_address' => ['nullable', 'string', 'max:500'],
            'company_logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'invoice_color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'invoice_prefix' => ['nullable', 'string', 'max:10'],
            'invoice_footer' => ['nullable', 'string', 'max:500'],
        ];
    }
}