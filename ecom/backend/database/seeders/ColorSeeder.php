<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['title' => 'Black',  'code' => '#000000'],
            ['title' => 'White',  'code' => '#FFFFFF'],
            ['title' => 'Red',    'code' => '#EF4444'],
            ['title' => 'Blue',   'code' => '#3B82F6'],
            ['title' => 'Green',  'code' => '#10B981'],
            ['title' => 'Yellow', 'code' => '#F59E0B'],
            ['title' => 'Gray',   'code' => '#6B7280'],
            ['title' => 'Navy',   'code' => '#1E3A8A'],
        ];

        foreach ($colors as $row) {
            Color::firstOrCreate(['title' => $row['title']], ['code' => $row['code']]);
        }
    }
}
