<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $updated = DB::table('admins')
        ->where('email', 'admin@masoung.com')
        ->update(['email' => 'skjccm@gmail.com']);
        
    if ($updated) {
        echo "Successfully updated admin email to: skjccm@gmail.com\n";
    } else {
        // شايد الإيميل تغير مسبقاً أو لم نجد السجل القديم، لنحاول البحث بأي إيميل متاح لو كان هناك ادمن واحد فقط
        $adminCount = DB::table('admins')->count();
        if ($adminCount == 1) {
            DB::table('admins')->update(['email' => 'skjccm@gmail.com']);
            echo "Successfully updated the only existing admin email to: skjccm@gmail.com\n";
        } else {
            echo "Could not find admin with email admin@masoung.com and multiple/no admins exist.\n";
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
