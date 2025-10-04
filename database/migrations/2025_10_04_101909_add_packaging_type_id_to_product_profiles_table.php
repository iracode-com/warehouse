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
            $table->unsignedBigInteger('packaging_type_id')->nullable()->comment('شناسه نوع بسته‌بندی');
            $table->foreign('packaging_type_id')->references('id')->on('packaging_types')->onDelete('set null');
            $table->index('packaging_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_profiles', function (Blueprint $table) {
            $table->dropForeign(['packaging_type_id']);
            $table->dropIndex(['packaging_type_id']);
            $table->dropColumn('packaging_type_id');
        });
    }
};
