<?php

namespace App\Filament\Admin\Resources\Teachers\Pages;

use App\Filament\Admin\Resources\Teachers\TeacherResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;


class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;

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
