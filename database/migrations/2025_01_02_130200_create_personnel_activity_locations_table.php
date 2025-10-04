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
        Schema::create('personnel_activity_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personnel_id')
                ->constrained('personnels', 'id', 'pal_personnel_id')
                ->onDelete('cascade');
            
            $table->foreignId('activity_location_id')
                ->constrained('activity_locations', 'id', 'pal_activity_location_id')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel_activity_locations');
    }
};
