<?php
$root = getcwd();
$path = $root . "/app/Providers/Filament/PanelProvider.php";

$content = "<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Plugins\Notifications;
use Illuminate\Support\ServiceProvider;
use App\Filament\Pages\Dashboard;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class PanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->login()
            ->id('admin')
            ->style('dark')
            ->path('admin')
            ->colors(['primary' => \Filament\Support\Colors\Color::Amber])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\Session\Middleware\AuthenticateSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
                \Illuminate\Foundation\Http\Middleware\SubstituteBindings::class,
                \Filament\Http\Middleware\DisableBladeIconComponents::class,
                \Filament\Http\Middleware\DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                \Filament\Http\Middleware\Authenticate::class,
            ]);
    }
}
";
file_put_contents($path, $content);
echo "PanelProvider created at: $path" . PHP_EOL;

$bootstrap = $root . "/bootstrap/providers.php";
$content = file_get_contents($bootstrap);
if (strpos($content, "PanelProvider") === false) {
    $content = str_replace(
        "App\\Providers\\RouteServiceProvider::class,",
        "App\\Providers\Filament\\PanelProvider::class,\n    App\\Providers\\RouteServiceProvider::class",
        $content
    );
    file_put_contents($bootstrap, $content);
    echo "  -> added to bootstrap/providers.php" . PHP_EOL;
} else {
    echo "  -> already registered" . PHP_EOL;
}
