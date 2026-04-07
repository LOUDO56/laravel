<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'string', 'max:255', 'unique:schools,name,' . $this->route('id')],
            'description' => ['nullable', 'string'],
            'address'     => ['nullable', 'string', 'max:500'],
        ];
    }
}
