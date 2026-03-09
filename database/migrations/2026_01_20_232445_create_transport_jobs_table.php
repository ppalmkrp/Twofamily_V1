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
        Schema::create('transport_jobs', function (Blueprint $table) {
            $table->id();

            $table->string('job_name')->nullable();

            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->string('start_point');
            $table->string('destination');
            $table->decimal('distance_km', 8, 2)->nullable();

            // 🔗 FK
            $table->string('truck_id', 45);
            $table->unsignedInteger('driver_id');
            $table->unsignedInteger('customer_id');

            $table->timestamps();

            $table->foreign('truck_id')
                ->references('id_truck')
                ->on('trucks');

            $table->foreign('driver_id')
                ->references('id_driver')
                ->on('drivers');

            $table->foreign('customer_id')
                ->references('id_customer')
                ->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_jobs');
    }
};
