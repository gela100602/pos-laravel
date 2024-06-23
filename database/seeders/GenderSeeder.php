<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genders = [
            ['gender' => 'Male'],
            ['gender' => 'Female'],
            ['gender' => 'Others'],
        ];

        DB::table('genders')->insert($genders);
    }
}