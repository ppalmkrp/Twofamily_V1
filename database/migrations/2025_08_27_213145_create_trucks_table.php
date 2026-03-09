<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trucks', function (Blueprint $table) {
            $table->string('id_truck', 45)->primary();
            $table->string('brand_truck');
            $table->string('model_truck')->nullable();
            $table->unsignedSmallInteger('year_truck')->nullable();
            $table->unsignedInteger('weight_truck')->nullable();
            $table->unsignedInteger('fuelfactory_truck')->nullable();

            $table->string('province_truck', 100);   // ✅ เพิ่ม
            $table->decimal('fuel_rate', 8, 2);      // ✅ เพิ่ม

            $table->enum('status_truck', ['active', 'maintenance', 'retired'])
                ->default('active');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trucks');
    }
};
