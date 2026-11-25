<?php

namespace App\Filament\Admin\Resources\PostCategories\Pages;

use App\Filament\Admin\Resources\PostCategories\PostCategoryResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreatePostCategory extends CreateRecord
{
    protected static string $resource = PostCategoryResource::class;

    public function mount(): void
    {
        if (! static::getResource()::canCreate()) {
            Notification::make('create_not_allowed')
                ->title('Akses Tidak Diizinkan')
                ->body('Anda tidak memiliki izin untuk menambahkan kategori berita.')
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
