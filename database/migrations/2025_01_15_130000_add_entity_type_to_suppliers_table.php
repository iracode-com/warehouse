<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('entity_type')->default('individual')->after('name')->comment('نوع شخصیت: individual, legal');
            $table->index('entity_type');
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropIndex(['entity_type']);
            $table->dropColumn('entity_type');
        });
    }
};
