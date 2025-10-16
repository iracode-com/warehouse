<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // فیلدهای تحویل گیرنده برای خروج کالا
            $table->string('delivery_first_name')->nullable()->after('party_phone')->comment('نام تحویل گیرنده');
            $table->string('delivery_last_name')->nullable()->after('delivery_first_name')->comment('نام خانوادگی تحویل گیرنده');
            $table->string('delivery_mobile')->nullable()->after('delivery_last_name')->comment('موبایل تحویل گیرنده');
            $table->string('delivery_receipt_image')->nullable()->after('delivery_mobile')->comment('تصویر رسید تحویل');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_first_name',
                'delivery_last_name', 
                'delivery_mobile',
                'delivery_receipt_image'
            ]);
        });
    }
};
