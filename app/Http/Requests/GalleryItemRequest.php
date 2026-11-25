<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GalleryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gallery_id'   => ['required', 'exists:web_galleries,id'],
            'type'         => ['required', 'string', Rule::in(['image', 'video', 'file'])],
            'file_or_url'  => ['required', 'string', 'max:255'],
            'caption'      => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'gallery_id.required'  => 'Galeri wajib diisi.',
            'gallery_id.exists'    => 'Galeri tidak valid.',
            'type.required'        => 'Tipe item wajib diisi.',
            'type.in'              => 'Tipe harus image, video, atau file.',
            'file_or_url.required' => 'File/URL wajib diisi.',
        ];
    }
}
