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
        Schema::create('billing_p_d_f_s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_order_fk');
            $table->foreign('sales_order_fk')->references('id')->on('sales_orders')->onUpdate('cascade')->onDelete('cascade');
            $table->string('pdf_url');
            $table->boolean('shared_via')->default(0); // 0 = Whatsapp , 1 = Email
            $table->dateTime('shared_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_p_d_f_s');
    }
};
