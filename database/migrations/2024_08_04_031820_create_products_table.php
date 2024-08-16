<?php

use App\Models\Category;
use App\Models\MeasurementUnit;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->decimal('price', 15);
            $table->foreignIdFor(MeasurementUnit::class, 'measurement_unit_id');
            $table->foreignIdFor(Category::class, 'category_id');
            $table->string('status');
            $table->foreignIdFor(User::class, 'last_modified_by_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
