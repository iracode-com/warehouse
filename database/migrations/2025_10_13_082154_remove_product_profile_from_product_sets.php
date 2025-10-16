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
        Schema::table('product_sets', function (Blueprint $table) {
            $table->dropForeign(['product_profile_id']);
            $table->dropColumn('product_profile_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_sets', function (Blueprint $table) {
            $table->unsignedBigInteger('product_profile_id')->after('id')->comment('شناسه کالای ست/سبد');
            $table->foreign('product_profile_id')->references('id')->on('product_profiles')->onDelete('cascade');
        });
    }
};
