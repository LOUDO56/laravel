<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject_id'       => ['required', 'exists:subjects,id'],
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'video_url'        => ['required', 'url', 'max:2048'],
            'duration_seconds' => ['nullable', 'integer', 'min:1'],
            'order'            => ['nullable', 'integer', 'min:0'],
        ];
    }
}
