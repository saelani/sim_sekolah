<?php
require "vendor/autoload.php";
use Illuminate\Support\Facades\DB;

$rows = DB::table('migrations')->orderBy('id')->get();
foreach ($rows as $r) {
    echo $r->migration . "\n";
}
echo "Total: " . $rows->count() . "\n";
