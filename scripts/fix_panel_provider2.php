<?php
$root = getcwd();
$p = $root . "/app/Providers/Filament/AdminPanelProvider.php";
$c = file_get_contents($p);

// Remove ->style("dark") line
$c = preg_replace("/            ->style\('dark'\);\n/", "", $c);

file_put_contents($p, $content);
echo "removed style()" . PHP_EOL;
