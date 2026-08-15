<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camp_truck', function (Blueprint $table) {
            $table->bigIncrements('id_assignment');
            $table->unsignedBigInteger('id_camp');
            $table->string('id_truck', 45);

            $table->date('assigned_date');                  // วันที่เริ่มทำงานที่แคมป์นี้
            $table->date('released_date')->nullable();      // วันที่ถอนออก (ว่าง = ยังทำงานอยู่)
            $table->string('note')->nullable();

            $table->timestamps();

            $table->foreign('id_camp')->references('id_camp')->on('camps')->onDelete('cascade');
            $table->foreign('id_truck')->references('id_truck')->on('trucks')->onDelete('cascade');

            $table->index(['id_camp', 'released_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camp_truck');
    }
};