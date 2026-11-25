<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

$base = '/home/saelani/lara/sim_sekolah';
require $base . '/vendor/autoload.php';

$app = require $base . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$bootstrapRequest = Request::create('/', 'GET');
$kernel->handle($bootstrapRequest);

// Create a test student account
DB::table('sys_users')->insert([
    'name' => 'Data Test Siswa',
    'email' => 'datates@sd.belajar.id',
    'email_verified_at' => Carbon::now(),
    'password' => Hash::make('datates123'),
    'remember_token' => null,
    'created_at' => Carbon::now(),
    'updated_at' => Carbon::now(),
]);
$userId = DB::getPdo()->lastInsertId();

// Check existing class_ids and students for reference
$classes = DB::table('acad_classes')->orderBy('id')->pluck('id')->toArray();
$firstClass = $classes[0] ?? 1;

DB::table('acad_students')->insert([
    'user_id' => $userId,
    'class_id' => $firstClass,
    'nis' => '9999991',
    'nisn' => '1111111111',
    'name' => 'Data Test Siswa',
    'gender' => 'L',
    'religion' => 'Islam',
    'pob' => 'Jakarta',
    'dob' => '2014-01-01',
    'address' => 'Test',
    'parent_name' => 'Test',
    'created_at' => Carbon::now(),
    'updated_at' => Carbon::now(),
]);
$studentId = DB::getPdo()->lastInsertId();
echo "Student id: $studentId, class_id: $firstClass\n";

// Insert sample savings (5 records)
for ($i = 0; $i < 5; $i++) {
    DB::table('ops_student_savings')->insert([
        'student_id' => $studentId,
        'user_id' => $userId,
        'date' => Carbon::now()->subDays($i)->format('Y-m-d'),
        'type' => 'in',
        'amount' => 10000 + $i * 5000,
        'description' => 'Penyetoran ' . $i,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ]);
}
echo "Inserted 5 savings\n";

// Insert sample attendances (8 records)
foreach (['Hadir', 'Sakit', 'Izin', 'Alpa'] as $idx => $status) {
    DB::table('ops_student_attendances')->insert([
        'class_id' => $firstClass,
        'student_id' => $studentId,
        'user_id' => $userId,
        'date' => Carbon::now()->subDays($idx % 8)->format('Y-m-d'),
        'status' => $status,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ]);
}
echo "Inserted 8 attendances\n";
