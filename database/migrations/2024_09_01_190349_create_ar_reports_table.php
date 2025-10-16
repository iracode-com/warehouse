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
