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
        Schema::table('product_set_items', function (Blueprint $table) {
            $table->dropForeign(['product_profile_id']);
            $table->renameColumn('product_profile_id', 'item_id');
        });

        Schema::table('product_set_items', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_set_items', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->renameColumn('item_id', 'product_profile_id');
        });

        Schema::table('product_set_items', function (Blueprint $table) {
            $table->foreign('product_profile_id')->references('id')->on('product_profiles')->onDelete('cascade');
        });
    }
};
