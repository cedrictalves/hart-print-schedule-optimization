<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Insert products into the database.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Product A', 'type' => 1],
            ['name' => 'Product B', 'type' => 1],
            ['name' => 'Product C', 'type' => 2],
            ['name' => 'Product D', 'type' => 3],
            ['name' => 'Product E', 'type' => 3],
            ['name' => 'Product F', 'type' => 1],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
