<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== DATABASE STRUCTURE ===\n\n";

echo "Tables:\n";
$tables = DB::select('SELECT name FROM sqlite_master WHERE type="table"');
foreach($tables as $table) {
    echo "- " . $table->name . "\n";
}

echo "\n=== ACTIVITIES TABLE ===\n";
$activityColumns = Schema::getColumnListing('activities');
foreach($activityColumns as $column) {
    echo "- " . $column . "\n";
}

echo "\n=== NEWS TABLE ===\n";
$newsColumns = Schema::getColumnListing('news');
foreach($newsColumns as $column) {
    echo "- " . $column . "\n";
}

echo "\n=== SAMPLE DATA ===\n";
$activity = DB::table('activities')->first();
if($activity) {
    echo "Activity ID: " . $activity->id . "\n";
    echo "Activity Title: " . $activity->title . "\n";
    echo "Activity Image: " . $activity->image . "\n";
}

$news = DB::table('news')->first();
if($news) {
    echo "\nNews ID: " . $news->id . "\n";
    echo "News Title: " . $news->title . "\n";
    echo "News Image: " . $news->image . "\n";
}
?>
