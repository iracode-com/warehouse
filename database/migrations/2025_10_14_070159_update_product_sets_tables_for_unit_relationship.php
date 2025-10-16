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
        // Update product_sets table
        Schema::table('product_sets', function (Blueprint $table) {
            $table->dropColumn('unit');
            $table->foreignId('unit_id')->nullable()->after('total_quantity')->constrained('units')->nullOnDelete();
        });

        // Update product_set_items table
        Schema::table('product_set_items', function (Blueprint $table) {
            $table->dropColumn('unit');
            $table->foreignId('unit_id')->nullable()->after('coefficient')->constrained('units')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse product_set_items table
        Schema::table('product_set_items', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
            $table->string('unit', 50)->nullable()->comment('واحد');
        });

        // Reverse product_sets table
        Schema::table('product_sets', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
            $table->string('unit', 50)->nullable()->comment('واحد');
        });
    }
};
