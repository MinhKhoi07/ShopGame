<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CHECKING MESSAGES TABLE ===\n\n";

$messages = DB::table('messages')
    ->orderBy('id', 'desc')
    ->limit(20)
    ->get();

echo "Total messages found: " . $messages->count() . "\n\n";

foreach ($messages as $msg) {
    echo "ID: {$msg->id} | User: {$msg->user_id} | Admin: {$msg->is_from_admin} | Message: {$msg->message} | Time: {$msg->created_at}\n";
}

echo "\n=== CHECKING FOR DUPLICATES ===\n\n";

$duplicates = DB::table('messages')
    ->select('user_id', 'message', 'created_at', DB::raw('COUNT(*) as count'))
    ->groupBy('user_id', 'message', 'created_at')
    ->having('count', '>', 1)
    ->get();

if ($duplicates->count() > 0) {
    echo "Found " . $duplicates->count() . " duplicate entries:\n";
    foreach ($duplicates as $dup) {
        echo "User: {$dup->user_id} | Message: {$dup->message} | Count: {$dup->count}\n";
    }
} else {
    echo "No duplicates found in database.\n";
}
