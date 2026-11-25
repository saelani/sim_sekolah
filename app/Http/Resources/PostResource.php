<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'category'    => $this->PostCategory ? [
                'id'   => $this->PostCategory->id,
                'name' => $this->PostCategory->name,
                'slug' => $this->PostCategory->slug,
            ] : null,
            'author'      => $this->User ? [
                'id'    => $this->User->id,
                'name'  => $this->User->name,
                'email' => $this->User->email,
            ] : null,
            'title'       => $this->title,
            'slug'        => $this->slug,
            'excerpt'     => $this->excerpt,
            'thumbnail'   => $this->thumbnail_path,
            'views'       => (int) $this->views_count,
            'is_published'=> (bool) $this->is_published,
            'published_at'=> $this->published_at,
        ];
    }
}
