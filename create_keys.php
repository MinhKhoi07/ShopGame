<?php

use App\Models\GameKey;
use App\Models\Game;

// Tạo game keys cho Minecraft
$game = Game::find(9);

if ($game) {
    echo "Creating keys for: {$game->name}\n";
    
    for ($i = 1; $i <= 5; $i++) {
        $keyCode = 'MC-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 16));
        
        GameKey::create([
            'game_id' => 9,
            'key_code' => $keyCode,
            'status' => 'available'
        ]);
        
        echo "Created key: {$keyCode}\n";
    }
    
    echo "\nSuccess! Created 5 keys.\n";
} else {
    echo "Game with ID 9 not found!\n";
}
