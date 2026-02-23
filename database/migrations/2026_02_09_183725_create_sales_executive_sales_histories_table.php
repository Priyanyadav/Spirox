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
        Schema::create('sales_executive_sales_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_exec_id');
            $table->foreign('sales_exec_id')->references('id')->on('sales_executives')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('sales_order_fk');
            $table->foreign('sales_order_fk')->references('id')->on('sales_orders')->onUpdate('cascade')->onDelete('cascade');
            $table->string('sale_amount');
            $table->date('sale_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_executive_sales_histories');
    }
};
