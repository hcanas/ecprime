<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quotation>
 */
class QuotationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference_number' => 'QR'.date('ymd').strtoupper(bin2hex(random_bytes(4))),
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'status' => ['draft', 'pending', 'sent', 'confirmed', 'cancelled'][rand(0, 4)],
        ];
    }
}
