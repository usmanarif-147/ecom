<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $title) {
            Size::firstOrCreate(['title' => $title]);
        }
    }
}
