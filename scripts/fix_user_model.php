<?php
$p = __DIR__ . '/../app/Models/User.php';
$c = file_get_contents($p);

$old = "use Illuminate\\Database\\Eloquent\\Model;";
$new = "use Illuminate\\Database\\Eloquent\\Model;\nuse Illuminate\\Notifications\\Notifiable;\nuse Laravel\\Sanctum\\HasApiTokens;";
if (strpos($c, $old) === false) {
    echo "OLD NOT FOUND" . PHP_EOL;
    exit(1);
}
$c = str_replace($old, $new, $c);

$old2 = "class User extends Model\n{\n    /**\n     * The table associated with the model.\n     */\n    protected \$table = 'sys_users';";
$new2 = "class User extends Model\n{\n    use HasApiTokens, Notifiable;\n\n    /**\n     * The table associated with the model.\n     */\n    protected \$table = 'sys_users';";
if (strpos($c, $old2) === false) {
    echo "OLD2 NOT FOUND" . PHP_EOL;
    exit(1);
}
$c = str_replace($old2, $new2, $c);

file_put_contents($p, $c);
echo "User.php updated OK" . PHP_EOL;
