<?php
$dir = __DIR__ . '/../database/migrations';
$count = 0;
foreach (glob($dir . '/*.php') as $f) {
    $c = file_get_contents($f);
    $old = '$table->unsignedBigInteger(' . chr(39) . 'id' . chr(39) . ')->primary();';
    $new = '$table->id();';
    $new = str_replace($old, $new, $c);
    if ($new !== $c) {
        file_put_contents($f, $new);
        $count++;
    }
}
echo "Files updated: $count" . PHP_EOL;
