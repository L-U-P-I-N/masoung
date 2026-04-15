<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Mock the admin user
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

$admin = DB::table('admins')->first();
if ($admin) {
    Session::put('admin_id', $admin->id);
    Session::put('admin_name', $admin->name);
    Session::put('isAdmin', true);
}

$response = $kernel->handle(
    $request = Illuminate\Http\Request::create('/admin/users', 'GET')
);

if ($response->getStatusCode() === 500) {
    echo "500 ERROR DETECTED\n";
    // Laravel usually returns the error html if debug is on
    echo substr($response->getContent(), 0, 2000);
} else {
    echo "Status: " . $response->getStatusCode() . "\n";
    // echo substr($response->getContent(), 0, 100);
}

$kernel->terminate($request, $response);
