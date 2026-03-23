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
        Schema::create('truck_models', function (Blueprint $table) {
            $table->id(); // รหัสรุ่น

            // เชื่อม FK ไปหายี่ห้อ (1 ยี่ห้อ มีได้หลายรุ่น)
            $table->foreignId('truck_brand_id')->constrained('truck_brands')->onDelete('cascade');

            $table->string('name_model'); // ชื่อรุ่น เช่น Victor 500, D-Max
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('truck_models');
    }
};
