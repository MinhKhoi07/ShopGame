<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo '=== ALL GAMES WITH IMAGES ===' . PHP_EOL;
$games = \App\Models\Game::all();
foreach($games as $game) {
    echo 'Game ' . $game->id . ': ' . $game->name . PHP_EOL;
    echo '  Thumbnail: ' . ($game->thumbnail ?? 'NULL') . PHP_EOL;
    echo '  Images: ' . ($game->images ? json_encode($game->images) : 'NULL') . PHP_EOL;
}
