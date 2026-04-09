<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- NEWS ---\n";
$news = DB::table('news')->orderBy('published_at', 'desc')->limit(5)->get();
foreach($news as $n) {
    echo "ID: {$n->id} | Title: {$n->title} | Image: [{$n->image}] | Published: {$n->published_at}\n";
}

echo "\n--- ACTIVITIES ---\n";
$acts = DB::table('activities')->orderBy('activity_date', 'desc')->limit(5)->get();
foreach($acts as $a) {
    echo "ID: {$a->id} | Title: {$a->title} | Image: [{$a->image}] | Date: {$a->activity_date}\n";
}
