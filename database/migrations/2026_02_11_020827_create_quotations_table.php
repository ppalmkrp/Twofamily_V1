<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {

            $table->bigIncrements('id_quot');

            $table->unsignedInteger('id_customer');

            $table->date('date_quot');
            $table->date('end_quot')->nullable();
            $table->enum('status', ['draft', 'sent', 'approved', 'rejected'])
                  ->default('draft');

            $table->integer('subtotal')->default(0);
            $table->integer('discount')->default(0);
            $table->integer('total_amount')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_customer')
                  ->references('id_customer')
                  ->on('customers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};