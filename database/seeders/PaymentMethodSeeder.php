<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethods = [
            ['method' => 'COD'],
            ['method' => 'Online Payments (GCash)'],
            ['method' => 'Credit/Debit Cards'],
        ];

        DB::table('payment_methods')->insert($paymentMethods);
    }
}