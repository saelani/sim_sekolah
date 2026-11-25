<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

$base = '/home/saelani/lara/sim_sekolah';
require $base . '/vendor/autoload.php';

$app = require $base . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Bootstrap the app (loads providers & facades) via a harmless request
$bootstrapRequest = Request::create('/', 'GET');
$kernel->handle($bootstrapRequest);

// Insert a test student account
DB::table('sys_users')->insert([
    'name' => 'Test Siswa',
    'email' => 'testsiswa@sd.belajar.id',
    'email_verified_at' => Carbon::now(),
    'password' => Hash::make('test12345'),
    'remember_token' => null,
    'created_at' => Carbon::now(),
    'updated_at' => Carbon::now(),
]);
$userId = DB::getPdo()->lastInsertId();
DB::table('acad_students')->insert([
    'user_id' => $userId,
    'class_id' => 1,
    'nis' => '99999',
    'nisn' => '0000000000',
    'name' => 'Test Siswa',
    'gender' => 'L',
    'religion' => 'Islam',
    'pob' => 'Jakarta',
    'dob' => '2014-01-01',
    'address' => 'Test',
    'parent_name' => 'Test',
    'created_at' => Carbon::now(),
    'updated_at' => Carbon::now(),
]);

echo "Test user inserted with id $userId\n";
