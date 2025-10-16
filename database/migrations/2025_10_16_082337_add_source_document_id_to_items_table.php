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
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('source_document_id')
                ->nullable()
                ->after('product_profile_id')
                ->constrained('documents')
                ->nullOnDelete()
                ->comment('سند مبدا - مشخص می‌کند این قلم از کدام سند ورودی آمده');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['source_document_id']);
            $table->dropColumn('source_document_id');
        });
    }
};
