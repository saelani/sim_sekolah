<?php

namespace App\Filament\Admin\Resources\Students\Pages;

use App\Filament\Admin\Resources\Students\StudentResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    public function mount(): void
    {
        if (! static::getResource()::canCreate()) {
            Notification::make('create_not_allowed')
                ->title('Akses Tidak Diizinkan')
                ->body('Anda tidak memiliki izin untuk menambahkan data siswa.')
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
