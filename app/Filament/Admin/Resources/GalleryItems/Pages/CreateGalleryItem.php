<?php

namespace App\Filament\Admin\Resources\GalleryItems\Pages;

use App\Filament\Admin\Resources\GalleryItems\GalleryItemResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateGalleryItem extends CreateRecord
{
    protected static string $resource = GalleryItemResource::class;

    public function mount(): void
    {
        if (! static::getResource()::canCreate()) {
            Notification::make('create_not_allowed')
                ->title('Akses Tidak Diizinkan')
                ->body('Anda tidak memiliki izin untuk menambahkan item galeri.')
                ->warning()
                ->send();

            $this->redirect(static::getResource()::getUrl('index'));
            return;
        }

        parent::mount();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
