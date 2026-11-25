<?php
require "vendor/autoload.php";
use Illuminate\Support\Str;

$dir = "database/migrations";
$files = array_values(array_diff(scandir($dir), ["", "."]));
$custom = [];
foreach ($files as $f) {
    if (strpos($f, ".php") === false) continue;
    if (preg_match("/^0001_/", $f)) continue;
    $custom[] = $f;
}
sort($custom);

$deps = [];
$tableForFile = [];
$fileForTable = [];
foreach ($custom as $f) {
    $base = substr($f, 0, -4);
    $createPos = strpos($base, "create_");
    $table = substr($base, $createPos + 7);
    $table = substr($table, 0, -6);
    $tableForFile[$table] = $f;
    $fileForTable[$f] = $table;
    $content = file_get_contents("$dir/$f");
    // Capture the ON() table name from foreign key definitions
    // (the arrow "->on(" so we don't false-match "json(" etc.)
    if (preg_match_all("/->on\(\s*[\x27\x60]([A-Za-z0-9_]+)[\x27\x60]/", $content, $m)) {
        $deps[$table] = $m[1];
    } else {
        $deps[$table] = [];
    }
}

echo "=== Dependencies (table -> depends on tables) ===\n";
foreach ($deps as $t => $refs) {
    echo "$t -> " . implode(", ", $refs) . "\n";
}

// Kahn's algorithm
$inDegree = array_fill_keys(array_keys($deps), 0);
foreach ($deps as $t => $refs) {
    foreach ($refs as $r) {
        if (isset($deps[$r])) {
            $inDegree[$t]++;
        }
    }
}

$queue = [];
foreach ($inDegree as $t => $deg) {
    if ($deg === 0) $queue[] = $t;
}
usort($queue, function($a, $b) use ($tableForFile) {
    return strcmp($tableForFile[$a], $tableForFile[$b]);
});
$order = [];
while (!empty($queue)) {
    $t = array_shift($queue);
    $order[] = $t;
    foreach ($deps as $other => $refs) {
        $key = array_search($t, $refs);
        if ($key !== false) {
            unset($deps[$other][$key]);
            $inDegree[$other]--;
            if ($inDegree[$other] === 0) {
                $queue[] = $other;
            }
        }
    }
    usort($queue, function($a, $b) use ($tableForFile) {
        return strcmp($tableForFile[$a], $tableForFile[$b]);
    });
}

echo "\n=== Topological Order ===\n";
foreach ($order as $t) {
    echo $t . "\n";
}

file_put_contents(__DIR__ . "/migration_order.txt", implode(PHP_EOL, $order));
echo "\nWrote " . count($order) . " files to migration_order.txt\n";
