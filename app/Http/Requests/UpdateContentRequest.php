<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'            => ['sometimes', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'video_url'        => ['sometimes', 'url', 'max:2048'],
            'duration_seconds' => ['nullable', 'integer', 'min:1'],
            'order'            => ['nullable', 'integer', 'min:0'],
        ];
    }
}
