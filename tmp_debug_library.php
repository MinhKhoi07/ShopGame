<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$lib = App\Models\Library::with(['orderItem.gameKey','game'])->first();
if (!$lib) {
    echo "no library\n";
    exit;
}

echo "Library ID: {$lib->id}\n";
echo "Order ID: {$lib->order_id}\n";
echo "Game ID: {$lib->game_id}\n";
echo "Game: {$lib->game->name}\n";
echo "OrderItem ID: " . ($lib->orderItem->id ?? 'null') . "\n";
echo "OrderItem game_key_id: " . ($lib->orderItem->game_key_id ?? 'null') . "\n";
echo "GameKey status: " . ($lib->orderItem->gameKey->status ?? 'null') . "\n";
echo "GameKey code: " . ($lib->orderItem->gameKey->key_code ?? 'null') . "\n";

$orderItems = App\Models\OrderItem::where('order_id', $lib->order_id)->get();
echo "\nOrder items for order {$lib->order_id}:\n";
foreach ($orderItems as $oi) {
    echo "- ID {$oi->id}, game_id {$oi->game_id}, game_key_id " . ($oi->game_key_id ?? 'null') . ", price {$oi->price}\n";
}

$relation = $lib->orderItem();
echo "\nRelation SQL: " . $relation->toSql() . "\n";
echo "Bindings: " . json_encode($relation->getBindings()) . "\n";
$relItem = $relation->first();
echo "Relation fetch ID: " . ($relItem->id ?? 'null') . "\n";
