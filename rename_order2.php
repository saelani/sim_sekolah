<?php
require "vendor/autoload.php";

$dir = "database/migrations";

// Read the topological order
$order = file(__DIR__ . "/migration_order.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$order = array_filter($order, fn($t) => trim($t) !== "");

echo "Topological order: " . count($order) . " tables\n";

$ts = 0;
foreach ($order as $table) {
    // Find current file for this table: filename should contain $table and "create_"
    $files = array_diff(scandir($dir), ["", "."]);
    $currentFile = null;
    foreach ($files as $f) {
        if (strpos($f, ".php") === false) continue;
        if (strpos($f, $table) !== false && strpos($f, "create_") !== false) {
            $currentFile = $f;
            break;
        }
    }

    if ($currentFile === null) {
        echo "WARNING: no file found for table '$table'\n";
        continue;
    }

    $newBase = "2024_01_01_" . str_pad($ts, 6, "0", STR_PAD_LEFT) . "_create_" . $table . "_table";
    $newPath = "$dir/$newBase.php";
    rename("$dir/$currentFile", $newPath);
    echo "  $currentFile -> $newBase.php\n";
    $ts++;
}

echo "\nRenamed " . count($order) . " files.\n";
