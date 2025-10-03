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
        Schema::create('rack_inspections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rack_id')->comment('شناسه قفسه');
            $table->unsignedBigInteger('inspector_id')->comment('شناسه بازرس');
            $table->date('inspection_date')->comment('تاریخ بازرسی');
            $table->enum('safety_status', [
                'standard', // استاندارد
                'needs_repair', // نیاز به تعمیر
                'critical', // بحرانی
                'out_of_service' // خارج از سرویس
            ])->comment('وضعیت ایمنی قفسه');
            $table->text('inspection_notes')->nullable()->comment('یادداشت‌های بازرسی');
            $table->json('issues_found')->nullable()->comment('مشکلات یافت شده');
            $table->date('next_inspection_date')->nullable()->comment('تاریخ بازرسی بعدی');
            $table->boolean('requires_maintenance')->default(false)->comment('نیاز به تعمیر');
            $table->timestamps();

            $table->foreign('rack_id')->references('id')->on('racks')->onDelete('cascade');
            $table->foreign('inspector_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rack_inspections');
    }
};
