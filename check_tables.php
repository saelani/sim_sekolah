<?php
require "vendor/autoload.php";
use Illuminate\Support\Facades\Schema;

$tables = ['acad_teachers', 'acad_classes', 'acad_subjects', 'sys_users'];
foreach ($tables as $t) {
    echo $t . ": " . (Schema::hasTable($t) ? "EXISTS" : "missing") . "\n";
}
