<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\MeasurementUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => bin2hex(random_bytes(16)).'.webp',
            'name' => fake()->unique()->words(rand(2, 10), true),
            'description' => [null, fake()->words(rand(5, 20), true)][rand(0, 1)],
            'price' => fake()->numberBetween(1, 5000),
            'measurement_unit_id' => MeasurementUnit::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'status' => ['available', 'unavailable', 'archived'][rand(0, 2)],
            'last_modified_by_id' => 1,
        ];
    }
}
