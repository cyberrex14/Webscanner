<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // nanti bisa dikunci pakai auth
    }

    public function rules(): array
    {
        return [
            'target_url' => [
                'required',
                'string',
                'max:255',
                'url',
                // hanya boleh http/https (anti SSRF basic)
                'regex:/^https?:\/\//i',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'target_url.required' => 'Target URL wajib diisi',
            'target_url.url' => 'Format URL tidak valid',
            'target_url.regex' => 'URL harus diawali http:// atau https://',
        ];
    }

    /**
     * Sanitasi input sebelum validasi
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('target_url')) {
            $url = trim($this->input('target_url'));

            // hapus trailing slash
            $url = rtrim($url, '/');

            $this->merge([
                'target_url' => $url
            ]);
        }
    }
}
