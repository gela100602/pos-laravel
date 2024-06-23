<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['category_name' => 'Writing'],
            ['category_name' => 'Artist Pens'],
            ['category_name' => 'Office'],
            ['category_name' => 'Fine Writing'],
            ['category_name' => 'Pen Accessories'],
        ];

        DB::table('categories')->insert($categories);
    }
}