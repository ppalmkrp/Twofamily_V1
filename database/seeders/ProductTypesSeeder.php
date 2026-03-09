<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_types')->insert([
            ['name_product_type' => 'หิน', 'createAt' => now()],
            ['name_product_type' => 'ดิน', 'createAt' => now()],
            ['name_product_type' => 'ทราย', 'createAt' => now()],
        ]);
    }
}
