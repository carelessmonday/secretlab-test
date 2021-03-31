<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetObjectRequest extends FormRequest {

    public function authorize(): bool
    {
        return TRUE;
    }

    public function rules(): array
    {
        return [
            'timestamp' => "sometimes|numeric"
        ];
    }
}
