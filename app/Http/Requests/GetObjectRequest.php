<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetObjectRequest extends FormRequest {

    public function authorize(): bool
    {
        return TRUE;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['key' => $this->route('key')]);
    }

    public function rules(): array
    {
        return [
            'timestamp' => 'sometimes|numeric',
            'key'       => 'required|bail|string|alpha_dash|min:3|max:50'
        ];
    }
}
