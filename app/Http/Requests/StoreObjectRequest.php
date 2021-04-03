<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Exception;

class StoreObjectRequest extends FormRequest {

    public function authorize(): bool
    {
        return TRUE;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['key' => $this->route('key')]);

        try {
            $objects = json_decode($this->getContent(), TRUE, 512, JSON_THROW_ON_ERROR);
            $keys = array_keys($objects);
            $this->merge([
                'objects'     => collect($objects)
                    ->map(function ($value) {
                        if (is_array($value)) {
                            return $value;
                        }

                        return $this->dealWithEncodedJSONString($value);
                    })
                    ->toArray(),
                'object_keys' => $keys
            ]);
        } catch (Exception $exception) {
            abort(406);
        }
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
            'objects.*.required'     => 'Value cannot be empty.',
            'object_keys.*.required' => 'Key cannot be empty.',
            'object_keys.*.string'   => 'Key must be a string.',
        ];
    }

    private function dealWithEncodedJSONString($value)
    {
        try {
            return json_decode($value, TRUE, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $exception) {
            return $value;
        }
    }
}
