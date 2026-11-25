<?php
require "vendor/autoload.php";

$dir = "database/migrations";

// Read the topological order
$order = file(__DIR__ . "/migration_order.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$order = array_filter($order, fn($t) => trim($t) !== "");

echo "Topological order: " . count($order) . " tables\n";

// Assign new timestamps and rename files
$ts = 0;
$renamed = 0;
foreach ($order as $table) {
    // Find current file for this table
    $currentFile = null;
    $files = array_diff(scandir($dir), ["", "."]);
    foreach ($files as $f) {
        if (strpos($f, ".php") === false) continue;
        $base = substr($f, 0, -4);
        // New filename format: {ts}_create_{table}_table.php
        $newBase = "2024_01_01_" . str_pad($ts, 6, "0", STR_PAD_LEFT) . "_create_" . $table . "_table";
        if (strpos($f, $table) !== false && strpos($f, "create_") !== false) {
            // Verify this is the right file
            $content = file_get_contents("$dir/$f");
            if (strpos($content, "class Create") !== false) {
                $currentFile = $f;
                break;
            }
        }
    }

    if ($currentFile === null) {
        echo "WARNING: no file found for table '$table'\n";
        continue;
    }

    $newPath = "$dir/$newBase.php";
    rename("$dir/$currentFile", $newPath);
    $renamed++;
    echo "  $currentFile -> $newBase.php\n";
    $ts++;
}

echo "\nRenamed $renamed files.\n";
