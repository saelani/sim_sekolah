<?php

$base = '/home/saelani/lara/sim_sekolah';
require $base . '/vendor/autoload.php';

$app = require $base . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/siswa/login', 'GET');
$response = $kernel->handle($request);

$router = $app->make(Illuminate\Routing\Router::class);
echo "=== Routes matching siswa/student/auth/logout ===\n";
foreach ($router->getRoutes()->getRoutes() as $route) {
    $uri = $route->uri();
    $methods = implode('|', $route->methods());
    $name = $route->getName();
    $action = $route->getActionName() ?? '';
    if (strpos($uri, 'siswa') !== false || strpos($action, 'StudentAuth') !== false || strpos($action, 'StudentDashboard') !== false || strpos($action, 'EnsureStudentAuthenticated') !== false) {
        echo $methods, ' | ', $uri, ' | name:[' . $name . '] | ', $action, "\n";
    }
}
echo "\n=== Total routes: " . count($router->getRoutes()->getRoutes()) . " ===\n";
