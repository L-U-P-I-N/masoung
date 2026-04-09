<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

try {
    $updated = DB::table('admins')
        ->where('email', 'admin@masoung.com')
        ->update(['password' => Hash::make('NewPass@123')]);
        
    if ($updated) {
        echo "Successfully updated password for admin@masoung.com\n";
    } else {
        echo "Could not find admin with email admin@masoung.com or password was already that value.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
