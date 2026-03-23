<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_records', function (Blueprint $table) {
            $table->id('id_fuel_record');
            $table->date('date_record');
            $table->string('start_point', 255);
            $table->text('start_detail')->nullable(); //เพิ่มบรรทัดนี้
            $table->string('destination', 255);
            $table->text('destination_detail')->nullable(); //เพิ่มบรรทัดนี้

            $table->integer('age_truck')->nullable();
            $table->integer('depreciation')->nullable();
            $table->integer('current_weight')->nullable();
            $table->integer('max_load')->nullable();
            $table->integer('distance')->nullable();

            // $table->integer('cost_fuel')->nullable();
            $table->decimal('cost_fuel', 10, 2)->nullable();
            $table->decimal('cost_fuel_total', 10, 2)->nullable(); //เพิ่มตรงนี้







            $table->timestamps();
            $table->softDeletes();

            // FK กับตาราง trucks
            $table->string('trucks_id_truck', 45);
            $table->foreign('trucks_id_truck')
                ->references('id_truck')->on('trucks')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_records');
    }
};
