<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'regular@example.com',
            'role' => 'regular',
            'status' => 'active',
        ]);

        User::factory()->create([
            'name' => 'Affiliate User',
            'email' => 'affiliate@example.com',
            'role' => 'affiliate',
            'status' => 'active',
        ]);

        User::factory(100)->create();

        MeasurementUnit::factory(20)->create();
        Category::factory(10)->hasSubCategories(rand(2, 5))->create();
        Product::factory(200)->create();

        User::where('role', 'affiliate')->get()->map(function ($affiliate) {
            Customer::factory()->create([
                'email' => $affiliate->email,
            ]);
        });

        Customer::factory(50)->create();

        for ($i = 0; $i < 500; $i++) {
            Quotation::factory()->hasItems(rand(5, 20))->create();
        }

        Quotation::where('status', 'confirmed')->get()->map(function ($quotation) {
            $purchase_order = PurchaseOrder::create([
                'reference_number' => 'PO' . date('ymd') . strtoupper(bin2hex(random_bytes(4))),
                'quotation_id' => $quotation->id,
                'status' => 'pending',
            ]);

            foreach ($quotation->items AS $item) {
                $purchase_order->items()->create([
                    'image' => $item->image,
                    'name' => $item->name,
                    'description' => $item->description,
                    'brand' => $item->brand,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'measurement_unit' => $item->measurement_unit,
                ]);
            }
        });
    }
}
