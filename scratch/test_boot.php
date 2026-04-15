<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "App booted successfully\n";

try {
    $config = config('backup');
    echo "Backup config loaded successfully\n";
} catch (\Throwable $e) {
    echo "Backup config failed: " . $e->getMessage() . "\n";
}
