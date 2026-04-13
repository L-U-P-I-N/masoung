<?php

define('LARAVEL_START', microtime(true));

// Adjust paths to root
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

try {
    // Mock session for admin
    Session::put('admin_id', 1);
    Session::put('admin_logged_in', true);
    
    $settings = DB::table('tribe_settings')->first();
    echo "Rendering admin.settings...\n";
    // We need to manually share $errors if we render outside of web middleware
    $view = View::make('admin.settings', ['settings' => $settings]);
    $view->with('errors', new \Illuminate\Support\ViewErrorBag());
    
    echo $view->render();
    echo "\nSuccess!\n";
} catch (\Throwable $e) {
    echo "Caught Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    // echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
