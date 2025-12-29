<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo '=== LIBRARY ===' . PHP_EOL;
$userId = 5;
$count = \App\Models\Library::where('user_id', $userId)->count();
echo "Total games in library for user $userId: " . $count . PHP_EOL;

$games = \App\Models\Library::where('user_id', $userId)->with('game')->get();
foreach($games as $lib) {
    echo "- " . $lib->game->name . " (Purchased: " . $lib->purchased_at . ")" . PHP_EOL;
}

echo PHP_EOL . '=== ORDERS ===' . PHP_EOL;
$orders = \App\Models\Order::where('user_id', 5)->with('orderItems.game')->get();
foreach($orders as $order) {
    echo 'Order #' . $order->id . ' - Status: ' . $order->status . ' - Total: ' . $order->total_amount . PHP_EOL;
    foreach($order->orderItems as $item) {
        echo '  - ' . $item->game->name . PHP_EOL;
    }
}
