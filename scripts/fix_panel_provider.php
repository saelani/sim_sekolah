<?php
$root = getcwd();

$content = "<?php\n\nnamespace App\Providers\Filament;\n\nuse Filament\Panel;\nuse Filament\PanelProvider;\nuse Illuminate\Support\ServiceProvider;\nuse App\Filament\Pages\Dashboard;\nuse Filament\Widgets\AccountWidget;\nuse Filament\Widgets\FilamentInfoWidget;\n\nclass PanelProvider extends PanelProvider\n{\n    public function panel(Panel \$panel): Panel\n    {\n        return \$panel\n            ->default()\n            ->login()\n            ->id('admin')\n            ->style('dark')\n            ->path('admin')\n            ->colors(['primary' => \\Filament\\Support\\Colors\\Color::Amber])\n            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')\n            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')\n            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')\n            ->pages([\n                Dashboard::class,\n            ])\n            ->widgets([\n                AccountWidget::class,\n                FilamentInfoWidget::class,\n            ])\n            ->middleware([\n                \\Illuminate\\Cookie\\Middleware\\EncryptCookies::class,\n                \\Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse::class,\n                \\Illuminate\\Session\\Middleware\\StartSession::class,\n                \\Illuminate\\Session\\Middleware\\AuthenticateSession::class,\n                \\Illuminate\\View\\Middleware\\ShareErrorsFromSession::class,\n                \\Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken::class,\n                \\Illuminate\\Foundation\\Http\\Middleware\\SubstituteBindings::class,\n                \\Filament\\Http\\Middleware\\DisableBladeIconComponents::class,\n                \\Filament\\Http\\Middleware\\DispatchServingFilamentEvent::class,\n            ])\n            ->authMiddleware([\n                \\Filament\\Http\\Middleware\\Authenticate::class,\n            ]);\n    }\n}\n";

file_put_contents($root . "/app/Providers/Filament/PanelProvider.php", $content);

$bootstrap = $root . "/bootstrap/providers.php";
$content = file_get_contents($bootstrap);
if (strpos($content, "PanelProvider") === false) {
    $content = str_replace(
        "App\\Providers\\RouteServiceProvider::class,",
        "App\\Providers\\Filament\\PanelProvider::class,\n    App\\Providers\\RouteServiceProvider::class",
        $content
    );
    file_put_contents($bootstrap, $content);
    echo "  -> added to bootstrap/providers.php" . PHP_EOL;
} else {
    echo "  -> already registered" . PHP_EOL;
}
echo "Done" . PHP_EOL;
