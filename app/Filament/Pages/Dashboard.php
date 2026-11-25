<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\ActionGroup;

class Dashboard extends Page
{
    protected string $view = 'filament.pages.dashboard';

    public function getTitle(): string
    {
        return 'Dashboard';
    }

    public function getHeading(): string
    {
        return 'Dashboard';
    }

    protected function setUp(): void
    {
        $this->description('Manajemen Sistem Informasi Sekolah');
    }
}
