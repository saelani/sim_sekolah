
<?php
require '/home/saelani/lara/sim_sekolah/vendor/autoload.php';

use Illuminate\Foundation\Application;

$app = new Application('/home/saelani/lara/sim_sekolah');
$app->configure(basePath: '/home/saelani/lara/sim_sekolah');
$app->withRouting(function ($routing) {
    $routing->web(function () {
        require '/home/saelani/lara/sim_sekolah/routes/web.php';
    });
});
$app->withMiddleware(function ($middleware) {});
$app->withExceptions(function ($exceptions) {});
$app->getProviders();
$app->register(\App\Providers\AppServiceProvider::class);
$app->register(\Livewire\LivewireServiceProvider::class);
$app->boot();

// Set DB config
$config = $app['config'];
$config->set('database.connections.mysql', [
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'port' => 3306,
    'database' => 'sim_sekolah',
    'username' => 'root',
    'password' => 'passwordbarumu',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);

// Get DB manager
use Illuminate\Support\Facades\DB;
DB::purge('mysql');
DB::reconnect('mysql', function ($connection) {
    $connection->setConfig([
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'sim_sekolah',
        'username' => 'root',
        'password' => 'passwordbarumu',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ]);
});

// Now render the view
use App\Models\SchoolClass;
use App\Models\SchoolProfile;
use App\Models\Teacher;

$class = SchoolClass::find(1);
$teacher = $class ? $class->homeroomTeacher : null;
$school = SchoolProfile::first();

$matrix = [
    'L' => array_fill_keys(range(6,14), 0),
    'P' => array_fill_keys(range(6,14), 0),
];
foreach ([6,7,8,9,10,11,12,13,14] as $age) {
    $matrix['L'][$age] = random_int(1, 5);
    $matrix['P'][$age] = random_int(1, 5);
}

$html = view('pdf.laporan-usia-bulanan', [
    'class' => $class,
    'school' => $school,
    'headmaster' => $teacher,
    'teacher' => $teacher,
    'year' => 2026,
    'month' => 10,
    'monthName' => 'Oktober 2026',
    'matrix' => $matrix,
    'ageRanges' => range(6,14),
    'titiMangsa' => '15 Oktober 2026',
])->render();

file_put_contents('/home/saelani/lara/sim_sekolah/storage/framework/views/_test_report.html', $html);
echo "HTML rendered successfully" . PHP_EOL;
echo "Length: " . strlen($html) . " chars" . PHP_EOL;
