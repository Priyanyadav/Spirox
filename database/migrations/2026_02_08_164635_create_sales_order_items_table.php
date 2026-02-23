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
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_order_fk');
            $table->foreign('sales_order_fk')->references('id')->on('sales_orders')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('product_fk');
            $table->foreign('product_fk')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->string('quantity');
            $table->string('price');
            $table->string('total');
            $table->string('gst');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_items');
    }
};
