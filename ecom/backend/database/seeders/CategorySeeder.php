<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['T-Shirts', 'Shirts', 'Jeans', 'Jackets', 'Hoodies', 'Shoes'] as $title) {
            Category::firstOrCreate(['title' => $title]);
        }
    }
}
