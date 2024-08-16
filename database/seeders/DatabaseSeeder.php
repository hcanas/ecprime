<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::factory(100)->create();

        MeasurementUnit::factory(20)->create();
        Category::factory(10)->hasSubCategories(rand(2, 5))->create();
        Product::factory(200)->create();
    }
}
