<?php
require "vendor/autoload.php";
use Illuminate\Support\Facades\DB;

// Check for acad_teachers table
$sql = "SHOW TABLES LIKE 'acad_teachers'";
$result = DB::select($sql);
echo "acad_teachers count: " . count($result) . "\n";
foreach ($result as $r) {
    echo "  " . $r->Tables_in_sim_sekolah . "\n";
}

// Also check the migrations table for any acad_teachers records
$sql2 = "SELECT migration FROM migrations WHERE migration LIKE '%acad_teachers%'";
$result2 = DB::select($sql2);
echo "acad_teachers migrations count: " . count($result2) . "\n";
foreach ($result2 as $r) {
    echo "  " . $r->migration . "\n";
}
