<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'supplier_name' => $this->faker->company,
            'address' => $this->faker->address,
            'email' => $this->faker->unique()->safeEmail,
            'contact_number' => $this->faker->phoneNumber,
        ];
    }

    /**
     * Configure the Faker data generation.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Supplier $supplier) {
            // Ensure the contact_number fits within a reasonable length
            $supplier->contact_number = $this->faker->numerify('+1 ###-###-####');
        });
    }
}
