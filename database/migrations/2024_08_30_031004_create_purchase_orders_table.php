<?php

use App\Models\Quotation;
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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->foreignIdFor(Quotation::class);
            $table->string('payment_details')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('status');
            $table->foreignIdFor(User::class, 'last_modified_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
