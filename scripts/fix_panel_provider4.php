<?php
$root = getcwd();
$p = $root . "/app/Providers/Filament/AdminPanelProvider.php";
$c = file_get_contents($p);
$c = str_replace("->style('dark');\n", "", $c);
file_put_contents($p, $c);
echo "removed style() " . PHP_EOL;
