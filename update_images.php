<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Restore GTA V - Game ID 4
\App\Models\Game::where('id', 4)->update([
    'thumbnail' => 'games/thumbnails/bPXB3sdfWVnIuBhGw5H2srwNlXg9vjJ7jBrzEmES.jpg'
]);

// Restore League of Legends - LoL - Game ID 18
\App\Models\Game::where('id', 18)->update([
    'thumbnail' => 'games/thumbnails/M1bZ04I8g8FXll52XEGK8MdAzX1FlIoo3lEho1KT.jpg'
]);

echo "Restored game images!" . PHP_EOL;

// Verify
$games = \App\Models\Game::whereIn('id', [4, 18])->get();
foreach($games as $game) {
    echo $game->name . ': ' . ($game->thumbnail ?? 'NULL') . PHP_EOL;
}
