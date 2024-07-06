<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Fetch all category IDs
        $categoryIds = DB::table('categories')->pluck('category_id')->toArray();

        return [
            'category_id' => $this->faker->randomElement($categoryIds),
            'supplier_id' => null, // Assuming nullable supplier_id as per your migration
            'product_name' => $this->faker->word,
            'purchase_price' => $this->faker->randomFloat(2, 1, 1000),
            'selling_price' => $this->faker->randomFloat(2, 1, 2000),
            'discount' => $this->faker->numberBetween(0, 50),
            'stock' => $this->faker->numberBetween(0, 100),
            'product_image' => $this->faker->imageUrl(),
            'is_deleted' => $this->faker->boolean(10), // 10% chance of being true
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
