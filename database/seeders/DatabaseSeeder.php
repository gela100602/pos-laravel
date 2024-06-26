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
        // Seed individual tables
        $this->call(GenderSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(DiscountSeeder::class);
        $this->call(PaymentMethodSeeder::class);

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
        // \App\Models\User::factory(10)->create();
    }
}