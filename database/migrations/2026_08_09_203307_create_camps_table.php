<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camps', function (Blueprint $table) {
            $table->bigIncrements('id_camp');
            $table->unsignedInteger('id_customer');      // ต้องเป็น int ให้ตรงกับ customers
            $table->string('code_camp', 20)->unique();   // CP-2569-0001

            $table->string('name_camp');

            $table->string('address_detail')->nullable();
            $table->string('subdistrict', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('zipcode', 5)->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->string('contact_name')->nullable();
            $table->string('contact_phone', 10)->nullable();

            $table->enum('status_camp', ['active', 'closed'])->default('active');
            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_customer')
                  ->references('id_customer')->on('customers')
                  ->onDelete('cascade');

            $table->index(['id_customer', 'status_camp']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camps');
    }
};