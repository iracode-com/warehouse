<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ar_reports', function (Blueprint $table) {
            $table->json('header')->nullable()->comment('سرتیتر گزارش به صورت JSON');
            $table->json('data')->nullable()->comment('داده‌های گزارش به صورت JSON');
            $table->json('grouping_rows')->nullable()->comment('ردیف‌های گروه‌بندی گزارش به صورت JSON');
            $table->json('query')->nullable()->comment('کوئری گزارش به صورت JSON');
            $table->string('font')->nullable()->comment('فونت گزارش');
            $table->string('export_type')->nullable()->comment('نوع خروجی گزارش (PDF، Excel و غیره)');
            $table->string('header_description')->nullable()->comment('توضیحات سرتیتر گزارش');
            $table->string('report_date')->nullable()->comment('تاریخ گزارش');
            $table->string('logo')->nullable()->comment('لوگوی گزارش');
            $table->string('footer_description')->nullable()->comment('توضیحات پایین گزارش');
            $table->integer('records_count')->nullable()->comment('تعداد رکوردهای گزارش');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ar_reports', function (Blueprint $table) {
            $table->dropColumn('header');
            $table->dropColumn('data');
            $table->dropColumn('grouping_rows');
            $table->dropColumn('query');
            $table->dropColumn('font');
            $table->dropColumn('export_type');
            $table->dropColumn('header_description');
            $table->dropColumn('report_date');
            $table->dropColumn('logo');
            $table->dropColumn('footer_description');
            $table->dropColumn('records_count');
        });
    }
};
