<?php

namespace Database\Factories;

use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\Quotation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuotationItem>
 */
class QuotationItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::inRandomOrder()->first();

        return [
            'quotation_id' => Quotation::inRandomOrder()->first()->id,
            'image' => $product->image,
            'name' => $product->name,
            'description' => $product->description,
            'brand' => [fake()->word, null][rand(0, 1)],
            'price' => $product->price,
            'quantity' => rand(1, 50),
            'measurement_unit' => MeasurementUnit::inRandomOrder()->first()->name,
            'status' => ['available', 'unavailable'][rand(0, 1)],
        ];
    }
}
