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
        Schema::create('live_location_trackings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_exec_id');
            $table->foreign('sales_exec_id')->references('id')->on('sales_executives')->onUpdate('cascade')->onDelete('cascade');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('location_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_location_trackings');
    }
};
