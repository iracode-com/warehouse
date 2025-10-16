<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Note: expiry_date already exists in the items table, so we only add production_date and batch_number
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (! Schema::hasColumn('items', 'production_date')) {
                $table->date('production_date')->nullable()->after('expiry_date')->comment('تاریخ تولید');
            }
            if (! Schema::hasColumn('items', 'batch_number')) {
                $table->string('batch_number', 100)->nullable()->after('production_date')->comment('شماره بچ/دسته');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['production_date', 'batch_number']);
        });
    }
};
