<?php

namespace Database\Seeders;

use App\Data\AdminStaticData;
use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $dir = public_path('images/seed-products');
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        foreach (AdminStaticData::products() as $row) {
            $category = Category::firstOrCreate(['title' => $row['category']]);

            $product = Product::firstOrCreate(
                ['title' => $row['name']],
                [
                    'category_id' => $category->id,
                    'description' => null,
                    'price'       => $row['price'],
                    'cost'        => 0,
                    'stock'       => $row['stock'],
                    'status'      => $row['status'] === 'active' ? ProductStatus::Active : ProductStatus::Inactive,
                ],
            );

            $filename     = "product-{$row['id']}.jpg";
            $absolutePath = "{$dir}/{$filename}";
            $relativePath = "images/seed-products/{$filename}";

            if (! file_exists($absolutePath)) {
                $response = Http::timeout(15)->get($row['image']);
                if ($response->successful()) {
                    file_put_contents($absolutePath, $response->body());
                } else {
                    continue;
                }
            }

            Image::firstOrCreate(
                ['product_id' => $product->id, 'path' => $relativePath],
            );
        }
    }
}
