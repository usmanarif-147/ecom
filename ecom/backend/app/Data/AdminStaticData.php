<?php

namespace App\Data;

class AdminStaticData
{
    public static function stats(): array
    {
        return [
            ['label' => 'Total Revenue',  'value' => '$24,389', 'change' => '+12.4%', 'positive' => true,  'icon' => 'dollar'],
            ['label' => 'Total Orders',   'value' => '1,284',   'change' => '+8.1%',  'positive' => true,  'icon' => 'cart'],
            ['label' => 'Total Products', 'value' => '12',      'change' => '+2',     'positive' => true,  'icon' => 'box'],
            ['label' => 'Customers',      'value' => '342',     'change' => '-3.2%',  'positive' => false, 'icon' => 'users'],
        ];
    }

    public static function categories(): array
    {
        return ['T-Shirts', 'Shirts', 'Jeans', 'Jackets', 'Hoodies', 'Shoes'];
    }

    public static function products(): array
    {
        return [
            ['id' => 1,  'name' => 'Classic Cotton T-Shirt', 'category' => 'T-Shirts', 'price' => 24.99,  'stock' => 42, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=200'],
            ['id' => 2,  'name' => 'Slim Fit Oxford Shirt',  'category' => 'Shirts',   'price' => 49.99,  'stock' => 18, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=200'],
            ['id' => 3,  'name' => 'Straight Leg Denim',     'category' => 'Jeans',    'price' => 69.99,  'stock' => 65, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=200'],
            ['id' => 4,  'name' => 'Bomber Jacket',          'category' => 'Jackets',  'price' => 119.00, 'stock' => 12, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=200'],
            ['id' => 5,  'name' => 'Pullover Hoodie',        'category' => 'Hoodies',  'price' => 54.99,  'stock' => 33, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=200'],
            ['id' => 6,  'name' => 'Low-Top Sneakers',       'category' => 'Shoes',    'price' => 89.99,  'stock' => 27, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=200'],
            ['id' => 7,  'name' => 'Graphic Print Tee',      'category' => 'T-Shirts', 'price' => 29.99,  'stock' => 51, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=200'],
            ['id' => 8,  'name' => 'Flannel Shirt',          'category' => 'Shirts',   'price' => 59.99,  'stock' => 22, 'status' => 'draft',  'image' => 'https://images.unsplash.com/photo-1608228088998-57828365d486?w=200'],
            ['id' => 9,  'name' => 'Skinny Fit Jeans',       'category' => 'Jeans',    'price' => 64.99,  'stock' => 40, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1582552938357-32b906df40cb?w=200'],
            ['id' => 10, 'name' => 'Wool Peacoat',           'category' => 'Jackets',  'price' => 199.00, 'stock' => 8,  'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=200'],
            ['id' => 11, 'name' => 'Zip-Up Hoodie',          'category' => 'Hoodies',  'price' => 64.99,  'stock' => 29, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=200'],
            ['id' => 12, 'name' => 'Running Trainers',       'category' => 'Shoes',    'price' => 109.00, 'stock' => 19, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1539185441755-769473a23570?w=200'],
        ];
    }

    public static function topProducts(): array
    {
        return array_slice(self::products(), 0, 4);
    }

    public static function orders(): array
    {
        return [
            ['id' => 'ORD-1042', 'customer' => 'Usman Ali',     'date' => '2026-05-04', 'items' => 3, 'total' => 134.97, 'status' => 'shipped'],
            ['id' => 'ORD-1041', 'customer' => 'Sara Khan',     'date' => '2026-05-03', 'items' => 1, 'total' => 49.99,  'status' => 'pending'],
            ['id' => 'ORD-1040', 'customer' => 'Ahmed Raza',    'date' => '2026-05-02', 'items' => 2, 'total' => 188.99, 'status' => 'delivered'],
            ['id' => 'ORD-1039', 'customer' => 'Fatima Noor',   'date' => '2026-05-01', 'items' => 1, 'total' => 64.99,  'status' => 'delivered'],
            ['id' => 'ORD-1038', 'customer' => 'Ali Hassan',    'date' => '2026-04-29', 'items' => 4, 'total' => 219.96, 'status' => 'shipped'],
            ['id' => 'ORD-1037', 'customer' => 'Mariam Yousaf', 'date' => '2026-04-27', 'items' => 1, 'total' => 109.00, 'status' => 'cancelled'],
            ['id' => 'ORD-1036', 'customer' => 'Bilal Tariq',   'date' => '2026-04-25', 'items' => 2, 'total' => 154.98, 'status' => 'delivered'],
            ['id' => 'ORD-1035', 'customer' => 'Hina Saeed',    'date' => '2026-04-22', 'items' => 1, 'total' => 24.99,  'status' => 'delivered'],
            ['id' => 'ORD-1034', 'customer' => 'Omar Sheikh',   'date' => '2026-04-20', 'items' => 3, 'total' => 199.00, 'status' => 'pending'],
            ['id' => 'ORD-1033', 'customer' => 'Zara Iqbal',    'date' => '2026-04-18', 'items' => 2, 'total' => 89.98,  'status' => 'delivered'],
        ];
    }

    public static function findProduct(int $id): ?array
    {
        foreach (self::products() as $p) {
            if ($p['id'] === $id) return $p;
        }
        return null;
    }
}
