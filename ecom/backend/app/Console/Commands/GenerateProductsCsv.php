<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateProductsCsv extends Command
{
    protected $signature = 'products:generate-csv {--only= : Generate one file with N rows instead of the default set}';

    protected $description = 'Generate test CSV files for the product import pipeline';

    private const HEADER = ['category', 'title', 'description', 'price', 'cost', 'stock', 'status', 'size', 'color'];

    private const DEFAULT_SIZES = [1000, 5000, 10000, 50000, 100000];

    private const VOCAB = [
        1 => [
            'adj1' => ['Vintage', 'Oversized', 'Boxy', 'Premium', 'Heavyweight', 'Soft', 'Cropped', 'Classic', 'Slim-Fit', 'Relaxed'],
            'adj2' => ['Distressed', 'Tie-Dye', 'Striped', 'Plain', 'Graphic', 'Pocket', 'Long-Sleeve', 'Short-Sleeve'],
            'noun' => ['T-Shirt', 'Tee', 'Crew Neck', 'V-Neck', 'Henley'],
        ],
        2 => [
            'adj1' => ['Casual', 'Formal', 'Linen', 'Oxford', 'Twill', 'Chambray', 'Western', 'Poplin', 'Slim', 'Tailored'],
            'adj2' => ['Mandarin Collar', 'Button-Down', 'Spread Collar', 'Cuban Collar', 'Pin Stripe', 'Checked'],
            'noun' => ['Shirt'],
        ],
        3 => [
            'adj1' => ['Slim', 'Skinny', 'Straight', 'Distressed', 'Acid Wash', 'Selvedge', 'Mid-Rise', 'High-Rise', 'Tapered', 'Light Wash', 'Dark Wash'],
            'adj2' => ['Stretch', 'Raw', 'Vintage', 'Bootcut', 'Wide Leg'],
            'noun' => ['Jeans', 'Denim'],
        ],
        4 => [
            'adj1' => ['Puffer', 'Bomber', 'Varsity', 'Wool', 'Trench', 'Hooded', 'Quilted', 'Field', 'Leather', 'Denim'],
            'adj2' => ['Lined', 'Heavy', 'Lightweight', 'Cropped', 'Long', 'Insulated'],
            'noun' => ['Jacket', 'Coat'],
        ],
        5 => [
            'adj1' => ['Cropped', 'Performance', 'Cotton Fleece', 'Tie-Dye', 'Zip-Up', 'Pullover', 'Oversized', 'Heavyweight', 'Sherpa'],
            'adj2' => ['Embroidered', 'Printed', 'Plain', 'Drawstring', 'Kangaroo Pocket'],
            'noun' => ['Hoodie', 'Sweatshirt'],
        ],
        6 => [
            'adj1' => ['Loafer', 'Court', 'Running', 'Slip-On', 'Canvas', 'Boat', 'Trainer', 'High-Top', 'Low-Top', 'Chunky'],
            'adj2' => ['Leather', 'Suede', 'Mesh', 'Knit', 'Retro', 'Performance'],
            'noun' => ['Sneakers', 'Shoes'],
        ],
    ];

    private const DESCRIPTIONS = [
        'Soft, breathable, and built to last through countless wears.',
        'Made with sustainably-sourced materials and ethical production.',
        'Lightweight construction perfect for layering or wearing solo.',
        'Crafted with attention to detail and quality materials.',
        'Featuring reinforced stitching and premium materials throughout.',
        'Versatile piece that transitions easily from day to night.',
        'A wardrobe staple with a tailored fit and refined finish.',
        'Engineered for performance with a comfortable, relaxed fit.',
        'Timeless design that pairs effortlessly with any outfit.',
        'An everyday essential that delivers on both function and form.',
        'Cut from premium fabric for everyday comfort and durability.',
        'Inspired by classic styles with a modern, refined twist.',
        'Designed in-house and finished with attention to every detail.',
        'Perfect blend of heritage craftsmanship and contemporary fit.',
    ];

    public function handle(): int
    {
        $only = $this->option('only');

        if ($only !== null) {
            if (! ctype_digit((string) $only) || (int) $only < 1) {
                $this->error('--only must be a positive integer');
                return self::FAILURE;
            }
            $targets = [(int) $only];
        } else {
            $targets = self::DEFAULT_SIZES;
        }

        foreach ($targets as $n) {
            $this->generate($n);
        }

        return self::SUCCESS;
    }

    private function generate(int $n): void
    {
        $filename = "products-{$n}.csv";
        $path = public_path($filename);
        $tmpPath = $path . '.tmp';

        mt_srand(crc32($filename));

        $fh = fopen($tmpPath, 'w');
        fputcsv($fh, self::HEADER);

        $recentTitles = [];
        $stats = [
            'rows' => 0,
            'duplicates' => 0,
            'missing_title' => 0,
            'empty_size' => 0,
            'empty_color' => 0,
        ];

        for ($i = 0; $i < $n; $i++) {
            $isDuplicate = count($recentTitles) > 0 && mt_rand(1, 100) <= 5;

            if ($isDuplicate) {
                [$category, $title] = $recentTitles[array_rand($recentTitles)];
                $stats['duplicates']++;
            } else {
                $category = mt_rand(1, 6);
                $title = $this->makeTitle($category);
                $recentTitles[] = [$category, $title];
                if (count($recentTitles) > 200) {
                    array_shift($recentTitles);
                }
            }

            if (mt_rand(1, 100) <= 3) {
                $title = '';
                $stats['missing_title']++;
            }

            $description = mt_rand(1, 100) <= 10
                ? ''
                : self::DESCRIPTIONS[array_rand(self::DESCRIPTIONS)];

            $price = mt_rand(1500, 30000) / 100;
            $cost = round($price * (mt_rand(40, 75) / 100), 2);
            $stock = mt_rand(0, 200);
            $status = mt_rand(0, 9) === 0 ? 0 : 1;

            $size = '';
            if (mt_rand(1, 100) <= 5) {
                $stats['empty_size']++;
            } else {
                $size = $this->pickIds(1, 6, mt_rand(1, 3));
            }

            $color = '';
            if (mt_rand(1, 100) <= 5) {
                $stats['empty_color']++;
            } else {
                $color = $this->pickIds(1, 8, mt_rand(1, 4));
            }

            fputcsv($fh, [
                $category,
                $title,
                $description,
                number_format($price, 2, '.', ''),
                number_format($cost, 2, '.', ''),
                $stock,
                $status,
                $size,
                $color,
            ]);
            $stats['rows']++;
        }

        fclose($fh);
        rename($tmpPath, $path);

        $this->line(sprintf(
            '%s: rows=%d | duplicates~%d | missing-title~%d | empty-size~%d | empty-color~%d',
            $filename,
            $stats['rows'],
            $stats['duplicates'],
            $stats['missing_title'],
            $stats['empty_size'],
            $stats['empty_color'],
        ));
    }

    private function makeTitle(int $category): string
    {
        $v = self::VOCAB[$category];
        $parts = [];

        if (mt_rand(1, 100) <= 60) {
            $parts[] = $v['adj1'][array_rand($v['adj1'])];
        }
        if (mt_rand(1, 100) <= 30) {
            $parts[] = $v['adj2'][array_rand($v['adj2'])];
        }
        $parts[] = $v['noun'][array_rand($v['noun'])];

        return implode(' ', $parts);
    }

    private function pickIds(int $min, int $max, int $count): string
    {
        $count = min($count, $max - $min + 1);
        $pool = range($min, $max);
        shuffle($pool);
        return implode('|', array_slice($pool, 0, $count));
    }
}
