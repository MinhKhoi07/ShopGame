<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo 'Syncing library for all users with paid orders...' . PHP_EOL;

$orders = \App\Models\Order::where('status', 'paid')->with('orderItems')->get();

$synced = 0;
foreach($orders as $order) {
    foreach($order->orderItems as $item) {
        $exists = \App\Models\Library::where('user_id', $order->user_id)
            ->where('game_id', $item->game_id)
            ->exists();
        
        if (!$exists) {
            \App\Models\Library::create([
                'user_id' => $order->user_id,
                'game_id' => $item->game_id,
                'order_id' => $order->id,
                'purchased_at' => $order->created_at,
            ]);
            echo 'Added game ' . $item->game_id . ' to user ' . $order->user_id . PHP_EOL;
            $synced++;
        }
    }
}

echo 'Synced ' . $synced . ' games.' . PHP_EOL;

echo PHP_EOL . '=== LIBRARY FOR USER 5 ===' . PHP_EOL;
$games = \App\Models\Library::where('user_id', 5)->with('game')->orderBy('purchased_at', 'desc')->get();
foreach($games as $lib) {
    echo "- " . $lib->game->name . PHP_EOL;
}
