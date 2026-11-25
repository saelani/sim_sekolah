<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GalleryItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'gallery_id' => $this->gallery_id,
            'type'       => $this->type,
            'file_or_url'=> $this->file_or_url,
            'caption'    => $this->caption,
        ];
    }
}
