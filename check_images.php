<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$activity = DB::table('activities')->latest('id')->first();

echo "Latest Activity:\n";
echo "ID: " . $activity->id . "\n";
echo "Title: " . $activity->title . "\n";
echo "Image field: " . $activity->image . "\n";

if ($activity->image) {
    $images = explode(',', $activity->image);
    echo "Images count: " . count($images) . "\n";
    foreach ($images as $index => $image) {
        echo "Image " . ($index + 1) . ": " . trim($image) . "\n";
    }
} else {
    echo "No images found\n";
}
?>
