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
use Illuminate\Support\Facades\DB;

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

        DB::beginTransaction();

        // create product categories
        $file = fopen(database_path('Categories.csv'), 'r');
        $temp_category = '';

        while (($data = fgetcsv($file)) !== FALSE) {
            if (empty($temp_category) OR $temp_category->name !== $data[0]) {
                $temp_category = Category::create(['name' => $data[0]]);
            }

            if (!empty($data[1])) {
                Category::create([
                    'name' => $data[1],
                    'parent_id' => $temp_category->id,
                ]);
            }
        }

        fclose($file);

        // create units of measurement
        $file = fopen(database_path('MeasurementUnits.csv'), 'r');

        while (($data = fgetcsv($file)) !== FALSE) {
            MeasurementUnit::create([
                'name' => $data[0],
            ]);
        }

        fclose($file);

        // create products
        // test kits
        $file = fopen(database_path('TestKits.csv'), 'r');

        while (($data = fgetcsv($file)) !== FALSE) {
            Product::create([
                'image' => bin2hex(random_bytes(16)).'.webp',
                'name' => $data[1],
                'description' => null,
                'price' => fake()->randomFloat(2, 100, 5000),
                'status' => ['available', 'unavailable', 'archived'][rand(0, 2)],
                'measurement_unit_id' => MeasurementUnit::where('name', $data[2])->first()->id,
                'category_id' => Category::where('name', $data[0])->first()->id,
                'created_at' => '2024-01-01 00:00:00',
                'updated_at' => '2024-01-01 00:00:00',
                'last_modified_by_id' => 1,
            ]);
        }

        fclose($file);

        // Vaccines
        $file = fopen(database_path('Vaccines.csv'), 'r');

        while (($data = fgetcsv($file)) !== FALSE) {
            Product::create([
                'image' => bin2hex(random_bytes(16)).'.webp',
                'name' => $data[1],
                'description' => null,
                'price' => fake()->randomFloat(2, 100, 5000),
                'status' => ['available', 'unavailable', 'archived'][rand(0, 2)],
                'measurement_unit_id' => MeasurementUnit::where('name', $data[2])->first()->id,
                'category_id' => Category::where('name', $data[0])->first()->id,
                'created_at' => '2024-01-01 00:00:00',
                'updated_at' => '2024-01-01 00:00:00',
                'last_modified_by_id' => 1,
            ]);
        }

        fclose($file);

        // Medicine Supplies
        $file = fopen(database_path('MedicineSupplies.csv'), 'r');

        while (($data = fgetcsv($file)) !== FALSE) {
            Product::create([
                'image' => bin2hex(random_bytes(16)).'.webp',
                'name' => $data[0],
                'description' => null,
                'price' => fake()->randomFloat(2, 100, 5000),
                'status' => ['available', 'unavailable', 'archived'][rand(0, 2)],
                'measurement_unit_id' => MeasurementUnit::where('name', $data[1])->first()->id,
                'category_id' => Category::where('name', 'Medicine Supplies')->first()->id,
                'created_at' => '2024-01-01 00:00:00',
                'updated_at' => '2024-01-01 00:00:00',
                'last_modified_by_id' => 1,
            ]);
        }

        fclose($file);

        DB::commit();

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
