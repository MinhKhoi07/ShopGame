<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Game;
use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo tài khoản admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@shopgame.com',
            'password' => Hash::make('12345678'),
            'is_admin' => true,
        ]);

        // Tạo tài khoản user thường
        User::create([
            'name' => 'User Demo',
            'email' => 'user@shopgame.com',
            'password' => Hash::make('12345678'),
            'is_admin' => false,
        ]);

        // Tạo categories
        $categories = [
            ['name' => 'Hành động', 'slug' => 'hanh-dong'],
            ['name' => 'RPG', 'slug' => 'rpg'],
            ['name' => 'Phiêu lưu', 'slug' => 'phieu-luu'],
            ['name' => 'Chiến thuật', 'slug' => 'chien-thuat'],
            ['name' => 'Thể thao', 'slug' => 'the-thao'],
            ['name' => 'Đua xe', 'slug' => 'dua-xe'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Tạo banners
        $banners = [
            [
                'title' => 'Chào mừng đến ShopGame',
                'description' => 'Khám phá hàng ngàn game và phần mềm chất lượng cao',
                'type' => 'slider',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Giảm giá lên đến 70%',
                'description' => 'Săn sale ngay hôm nay',
                'type' => 'slider',
                'display_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }

        // Tạo games mẫu
        $games = [
            [
                'category_id' => 1,
                'name' => 'Cyberpunk 2077',
                'slug' => 'cyberpunk-2077',
                'description' => 'Game nhập vai thế giới mở trong tương lai đầy cyberpunk',
                'price' => 1299000,
                'price_sale' => 649000,
                'thumbnail' => 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=800&h=400&fit=crop',
                'developer' => 'CD Projekt Red',
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'The Witcher 3',
                'slug' => 'the-witcher-3',
                'description' => 'Hành trình của thợ săn quái vật Geralt of Rivia',
                'price' => 599000,
                'price_sale' => 299000,
                'thumbnail' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=800&h=400&fit=crop',
                'developer' => 'CD Projekt Red',
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Elden Ring',
                'slug' => 'elden-ring',
                'description' => 'Thế giới fantasy khổng lồ từ FromSoftware',
                'price' => 1299000,
                'price_sale' => 899000,
                'thumbnail' => 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=800&h=400&fit=crop',
                'developer' => 'FromSoftware',
                'is_active' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'GTA V',
                'slug' => 'gta-v',
                'description' => 'Thế giới mở tội phạm nổi tiếng nhất',
                'price' => 499000,
                'price_sale' => 199000,
                'thumbnail' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&h=400&fit=crop',
                'developer' => 'Rockstar Games',
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Baldur\'s Gate 3',
                'slug' => 'baldurs-gate-3',
                'description' => 'RPG chiến thuật lượt với câu chuyện sâu sắc',
                'price' => 1299000,
                'thumbnail' => 'https://images.unsplash.com/photo-1612287230202-1ff1d85d1bdf?w=800&h=400&fit=crop',
                'developer' => 'Larian Studios',
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Starfield',
                'slug' => 'starfield',
                'description' => 'Khám phá vũ trụ trong RPG khoa học viễn tưởng',
                'price' => 1499000,
                'thumbnail' => 'https://images.unsplash.com/photo-1493711662062-fa541adb3fc8?w=800&h=400&fit=crop',
                'developer' => 'Bethesda Game Studios',
                'is_active' => true,
            ],
            [
                'category_id' => 3,
                'name' => 'Hogwarts Legacy',
                'slug' => 'hogwarts-legacy',
                'description' => 'Trải nghiệm thế giới phép thuật Harry Potter',
                'price' => 999000,
                'thumbnail' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?w=800&h=400&fit=crop',
                'developer' => 'Avalanche Software',
                'is_active' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Resident Evil 4',
                'slug' => 'resident-evil-4',
                'description' => 'Phiên bản remake của tựa game kinh dị huyền thoại',
                'price' => 899000,
                'thumbnail' => 'https://images.unsplash.com/photo-1486572788966-cfd3df1f5b42?w=800&h=400&fit=crop',
                'developer' => 'Capcom',
                'is_active' => true,
            ],
        ];

        foreach ($games as $game) {
            Game::create($game);
        }
    }
}

