<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->increments('id_driver');

            $table->string('name_driver', 255);

            // ที่อยู่
            $table->string('address_detail', 255)->nullable(); // บ้านเลขที่ / หมู่
            $table->string('subdistrict', 100)->nullable();    // ตำบล
            $table->string('district', 100)->nullable();       // อำเภอ
            $table->string('province', 100)->nullable();       // จังหวัด (string)
            $table->string('zipcode', 5)->nullable();          // รหัสไปรษณีย์

            $table->string('phone_driver', 10)->nullable();
            $table->string('citizenid_driver', 13)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
