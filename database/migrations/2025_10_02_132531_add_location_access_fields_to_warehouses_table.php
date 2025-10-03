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
        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('urban_location')->nullable()->after('natural_hazards');
            $table->string('main_road_access')->nullable()->after('urban_location');
            $table->string('heavy_vehicle_access')->nullable()->after('main_road_access');
            $table->json('terminal_proximity')->nullable()->after('heavy_vehicle_access');
            $table->string('parking_facilities')->nullable()->after('terminal_proximity');
            $table->json('utilities')->nullable()->after('parking_facilities');
            $table->json('neighboring_organizations')->nullable()->after('utilities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn([
                'urban_location',
                'main_road_access',
                'heavy_vehicle_access',
                'terminal_proximity',
                'parking_facilities',
                'utilities',
                'neighboring_organizations'
            ]);
        });
    }
};
