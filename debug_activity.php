<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== DEBUG ACTIVITY CREATION ===\n";

// Simulate request data
$data = [
    'title' => 'Test Activity',
    'description' => 'Test Description',
    'content' => 'Test Content',
    'activity_date' => date('Y-m-d'),
    'location' => 'Test Location',
    'image' => 'test1.jpg,test2.jpg', // Simulate comma-separated images
    'is_published' => 1,
    'created_at' => now(),
    'updated_at' => now()
];

echo "Data to insert:\n";
print_r($data);

try {
    $id = DB::table('activities')->insertGetId($data);
    echo "\nSUCCESS: Activity inserted with ID: " . $id . "\n";
    
    // Verify insertion
    $inserted = DB::table('activities')->find($id);
    echo "Image field in database: " . $inserted->image . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
