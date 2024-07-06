<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $discounts = [
            ['discount_type' => 'Item Discount', 'percentage' => 10],
            ['discount_type' => 'Item Discount', 'percentage' => 20],
        ];

        DB::table('discounts')->insert($discounts);
    }
}