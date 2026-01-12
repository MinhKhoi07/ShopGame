<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== DELETING DUPLICATE MESSAGES ===\n\n";

// Lấy danh sách các tin nhắn duplicate
$duplicates = DB::select("
    SELECT user_id, message, created_at, MIN(id) as keep_id
    FROM messages
    GROUP BY user_id, message, created_at
    HAVING COUNT(*) > 1
");

$totalDeleted = 0;

foreach ($duplicates as $dup) {
    // Xóa tất cả các bản ghi duplicate, giữ lại bản ghi có id nhỏ nhất
    $deleted = DB::delete("
        DELETE FROM messages 
        WHERE user_id = ? 
        AND message = ? 
        AND created_at = ? 
        AND id != ?
    ", [$dup->user_id, $dup->message, $dup->created_at, $dup->keep_id]);
    
    $totalDeleted += $deleted;
    echo "Deleted {$deleted} duplicate(s) for message: '{$dup->message}'\n";
}

echo "\n=== RESULT ===\n";
echo "Total duplicates deleted: {$totalDeleted}\n";

// Hiển thị lại danh sách tin nhắn
echo "\n=== REMAINING MESSAGES ===\n";
$remaining = DB::table('messages')
    ->orderBy('id', 'desc')
    ->limit(10)
    ->get();

foreach ($remaining as $msg) {
    echo "ID: {$msg->id} | User: {$msg->user_id} | Message: {$msg->message}\n";
}
