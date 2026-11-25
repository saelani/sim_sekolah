<?php

namespace App\Filament\Admin\Resources\Galleries\Pages;

use App\Filament\Admin\Resources\Galleries\GalleryResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateGallery extends CreateRecord
{
    protected static string $resource = GalleryResource::class;

    public function mount(): void
    {
        if (! static::getResource()::canCreate()) {
            Notification::make('create_not_allowed')
                ->title('Akses Tidak Diizinkan')
                ->body('Anda tidak memiliki izin untuk menambahkan galeri.')
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
