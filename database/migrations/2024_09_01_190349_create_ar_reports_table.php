<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ar_reports', function (Blueprint $table) {
            $table->id()->comment('شناسه یکتا');

            $table->foreignIdFor(User::class, 'created_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete()
                ->comment('ایجاد شده توسط');

            $table->foreignIdFor(User::class, 'updated_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete()
                ->comment('آخرین بار ویرایش شده توسط');

            $table->string('reportable_type')->comment('نوع منبع گزارش‌دهی (پلی‌مورفیک)');
            $table->string('reportable_resource')->comment('شناسه منبع گزارش‌دهی (پلی‌مورفیک)');
            $table->string('title')->comment('عنوان گزارش');
            $table->text('description')->nullable()->comment('توضیحات گزارش');
            $table->tinyInteger('step')->default(0)->comment('مرحله گزارش');
            
            // Additional fields
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

            $table->timestamps();
            $table->softDeletes();

            $table->comment('جدول گزارش ها');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ar_reports');
    }
};
