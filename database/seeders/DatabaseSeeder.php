<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\ProductSeeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the seeders to insert the products in the database.
     */
    public function run(): void
    {
        $this->call(ProductSeeder::class);
    }
}
