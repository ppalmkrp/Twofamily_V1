<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id('id_iv');
            $table->date('date_iv');
            $table->date('end_iv')->nullable();
            $table->enum('status', ['ค้างชำระ', 'ชำระแล้ว', 'ยกเลิก'])->default('ค้างชำระ');
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('Users_id_user')->nullable();
            $table->unsignedInteger('Customers_id_customer')->nullable(); 
            $table->unsignedBigInteger('id_address')->nullable();         

            $table->foreign('Users_id_user')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->foreign('Customers_id_customer')
                ->references('id_customer')->on('customers')
                ->nullOnDelete();

            $table->foreign('id_address')
                ->references('id')->on('address')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
