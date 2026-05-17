<?php

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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone_numer');
            $table->string('customer_address');
            $table->string('payment_method');
            $table->integer('number_of_items')->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->tinyInteger('status')->comment('0 => pending, 1 => shipped, 2 => delivered, 3 => canceled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
