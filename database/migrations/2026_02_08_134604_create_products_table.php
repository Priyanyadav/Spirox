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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_fk');
            $table->foreign('category_fk')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('image');
            $table->string('name');
            $table->string('weight');
            $table->string('price');
            $table->enum('variation_gram', ['100g', '200g', '500g', '1kg']);
            $table->string('gst');
            $table->boolean('status')->default(0);
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
