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
        Schema::table('document_items', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropColumn('item_id');
            $table->foreignId('product_profile_id')->after('document_id')->constrained('product_profiles')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_items', function (Blueprint $table) {
            $table->dropForeign(['product_profile_id']);
            $table->dropColumn('product_profile_id');
            $table->foreignId('item_id')->after('document_id')->constrained('items')->restrictOnDelete();
        });
    }
};
