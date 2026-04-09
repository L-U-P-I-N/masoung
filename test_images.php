<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

echo "=== TEST IMAGE UPLOAD ===\n";

// Simulate request with images
$request = new Request([
    'title' => 'Test with Images',
    'description' => 'Test Description',
    'content' => 'Test Content',
    'activity_date' => '2026-04-08',
    'location' => 'Test Location',
    'images' => [
        // Simulate uploaded files - this won't actually work in this test
    ]
]);

echo "Request has images: " . ($request->hasFile('images') ? 'YES' : 'NO') . "\n";
echo "Request all data: " . print_r($request->all(), true) . "\n";

// Test the logic from controller
$imagePaths = [];
if ($request->hasFile('images')) {
    echo "Processing images...\n";
    foreach ($request->file('images') as $image) {
        $path = $image->store('activities', 'public');
        $imagePaths[] = $path;
        echo "Stored image: " . $path . "\n";
    }
} else {
    echo "No images found in request\n";
}

if (!empty($imagePaths)) {
    $imageField = implode(',', $imagePaths);
    echo "Final image field: " . $imageField . "\n";
} else {
    echo "No images to process\n";
}
?>
