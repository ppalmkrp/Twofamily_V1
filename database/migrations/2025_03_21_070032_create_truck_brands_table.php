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
        Schema::create('truck_brands', function (Blueprint $table) {
            $table->id(); // รหัสยี่ห้อ (Auto Increment)
            $table->string('name_brand')->unique(); // ชื่อยี่ห้อ เช่น HINO, ISUZU
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('truck_brands');
    }
};
