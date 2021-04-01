<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreObjectRequest extends FormRequest {

    public function authorize(): bool
    {
        return TRUE;
    }

    public function rules(): array
    {
        return [
            'objects.*'     => 'required',
            'object_keys.*' => 'required|string|alpha_dash|min:3|max:50'
        ];
    }

    public function messages(): array
    {
        return [
            'objects.*.required' => 'Value is required.',
            'object_keys.*.required' => 'Key is required.',
            'object_keys.*.string' => 'Key is must be a string.',
        ];
    }
}
