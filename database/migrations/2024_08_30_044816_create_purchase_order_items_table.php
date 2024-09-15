<?php

use App\Models\PurchaseOrder;
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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PurchaseOrder::class);
            $table->string('image');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('brand')->nullable();
            $table->decimal('price', 15);
            $table->unsignedInteger('quantity');
            $table->string('measurement_unit');
            $table->foreignIdFor(User::class, 'last_modified_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
