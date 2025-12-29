<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo '=== GAMES IN LIBRARY FOR USER 5 ===' . PHP_EOL;
$games = \App\Models\Library::where('user_id', 5)->with('game')->get();
foreach($games as $lib) {
    echo 'Game: ' . $lib->game->name . PHP_EOL;
    echo '  ID: ' . $lib->game->id . PHP_EOL;
    echo '  Image URL: ' . ($lib->game->image_url ?? 'NULL') . PHP_EOL;
    echo '  Category ID: ' . ($lib->game->category_id ?? 'NULL') . PHP_EOL;
    echo PHP_EOL;
}
