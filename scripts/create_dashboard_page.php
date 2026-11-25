<?php
$root = getcwd();
$path = $root . "/app/Filament/Pages/Dashboard.php";

$content = "<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Screen;
use Filament\Actions\ActionGroup;

class Dashboard extends Page
{
    use Screen;

    protected static string $view = 'filament.pages.dashboard';

    protected static string $slug = 'dashboard';

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
        $this->description('Manage your school management system here.');
    }
}
";
file_put_contents($path, $content);
echo "Dashboard page created at: $path" . PHP_EOL;
