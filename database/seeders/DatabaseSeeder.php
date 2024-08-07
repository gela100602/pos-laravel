<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /* \App\Models\Product::factory(10)->create(); */
        // Seed individual tables
        $this->call(GenderSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(DiscountSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        \App\Models\Supplier::factory(5)->create();
        \App\Models\Customer::factory(5)->create();
        \App\Models\Product::factory(15)->create();


        // Seed Users table
        $this->seedUsers();
    }

    /**
     * Seed the users table.
     *
     * @return void
     */
    private function seedUsers()
    {
        // Admin User
        DB::table('users')->insert([
             'role_id' => 1,
             'gender_id' => rand(1, 3),
             'name' => 'Angela Bartolo Arguelles',
             'email' => 'angelaarguelles04@gmail.com',
             'username' => 'admin',
             'password' => Hash::make('123'),
             'contact_number' => '123-456-7890',
             'user_image' => 'user_image/default-user.png',
        ]);

        // Example: Create more users using factories
        // \App\Models\Product::factory(10)->create();

        // DB::table('users')->insert([
        //     'name' => 'Angela Arguelles',
        //     'email' => 'angelaarguelles04@gmail.com',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('123'),
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
    }
}