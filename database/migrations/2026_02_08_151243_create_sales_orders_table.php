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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_executive_fk');
            $table->foreign('sales_executive_fk')->references('id')->on('sales_executives')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('store_fk');
            $table->foreign('store_fk')->references('id')->on('stores')->onUpdate('cascade')->onDelete('cascade');
            $table->string('order_date');
            $table->string('total_amount');
            $table->boolean('payment_status')->default(0); // 0 = Paid , 1 = Pending
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
