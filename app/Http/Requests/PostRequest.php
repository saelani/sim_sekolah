<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'category_id' => ['required', 'exists:web_post_categories,id'],
            'user_id'     => ['required', 'exists:sys_users,id'],
            'title'       => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', Rule::unique('web_posts')->ignore($this->route('post') ?? $this->post['id'] ?? null)],
            'excerpt'     => ['required', 'string', 'max:500'],
            'content'     => ['required', 'string'],
            'thumbnail_path' => ['nullable', 'string', 'max:255'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required'     => 'Pilih kategori berita.',
            'category_id.exists'       => 'Kategori berita tidak valid.',
            'user_id.required'         => 'Penulis berita wajib diisi.',
            'user_id.exists'           => 'Pengguna tidak valid.',
            'title.required'           => 'Judul berita wajib diisi.',
            'slug.required'            => 'Slug wajib diisi.',
            'slug.unique'              => 'Slug sudah digunakan.',
            'excerpt.required'         => 'Ekstrak/pengantar wajib diisi.',
            'content.required'         => 'Isi berita wajib diisi.',
        ];
    }
}
