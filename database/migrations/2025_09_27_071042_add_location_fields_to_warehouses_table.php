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
            $table->unsignedBigInteger('province_id')->nullable()->after('branch_id');
            $table->unsignedBigInteger('city_id')->nullable()->after('province_id');
            $table->unsignedBigInteger('town_id')->nullable()->after('city_id');
            $table->unsignedBigInteger('village_id')->nullable()->after('town_id');
            
            $table->foreign('province_id')->references('id')->on('regions')->onDelete('set null');
            $table->foreign('city_id')->references('id')->on('regions')->onDelete('set null');
            $table->foreign('town_id')->references('id')->on('regions')->onDelete('set null');
            $table->foreign('village_id')->references('id')->on('regions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['town_id']);
            $table->dropForeign(['village_id']);
            
            $table->dropColumn(['province_id', 'city_id', 'town_id', 'village_id']);
        });
    }
};
