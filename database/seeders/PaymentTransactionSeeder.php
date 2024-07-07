<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transactions = [
            [
                'customer_id' => 1,
                'total_items' => 5,
                'total_price' => 50.00,
                'discount_id' => 0,
                'payment' => 50.00,
                'received' => 50.00,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 3,
                'total_items' => 3,
                'total_price' => 30.00,
                'discount_id' => 1,
                'payment' => 25.00,
                'received' => 25.00,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('payment_transactions')->insert($transactions);
    }
}
