<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id('id_iv_detail');
            $table->integer('quantity')->default(1);
            $table->string('unit', 45)->nullable();
            $table->integer('total_price')->default(0);
            $table->integer('subtotal_quot')->default(0);
            $table->integer('discount_quot')->default(0);
            $table->integer('tax_quot')->default(0);
            $table->integer('total_amount')->default(0);
            $table->string('payment_terms', 255)->nullable();
            $table->timestamps(2);
            $table->softDeletes();

            $table->unsignedBigInteger('Products_id_product')->nullable();
            $table->unsignedBigInteger('Invoice_id_iv')->nullable();

            $table->foreign('Products_id_product')->references('id_product')->on('products')->nullOnDelete();
            $table->foreign('Invoice_id_iv')->references('id_iv')->on('invoices')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
