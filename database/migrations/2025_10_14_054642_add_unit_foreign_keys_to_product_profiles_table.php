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
        Schema::table('product_profiles', function (Blueprint $table) {
            // تغییر نوع فیلدها از string به unsignedBigInteger
            $table->unsignedBigInteger('unit_of_measure_id')->nullable()->after('unit_of_measure')->comment('شناسه واحد اندازه‌گیری');
            $table->unsignedBigInteger('primary_unit_id')->nullable()->after('primary_unit')->comment('شناسه واحد شمارش اصلی');
            $table->unsignedBigInteger('secondary_unit_id')->nullable()->after('secondary_unit')->comment('شناسه واحد شمارش فرعی');

            // افزودن foreign keys
            $table->foreign('unit_of_measure_id')->references('id')->on('units')->onDelete('set null');
            $table->foreign('primary_unit_id')->references('id')->on('units')->onDelete('set null');
            $table->foreign('secondary_unit_id')->references('id')->on('units')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_profiles', function (Blueprint $table) {
            $table->dropForeign(['unit_of_measure_id']);
            $table->dropForeign(['primary_unit_id']);
            $table->dropForeign(['secondary_unit_id']);

            $table->dropColumn('unit_of_measure_id');
            $table->dropColumn('primary_unit_id');
            $table->dropColumn('secondary_unit_id');
        });
    }
};
