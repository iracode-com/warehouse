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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rule_id')->comment('شناسه قانون');
            $table->unsignedBigInteger('category_id')->nullable()->comment('شناسه دسته‌بندی');
            $table->unsignedBigInteger('attribute_id')->nullable()->comment('شناسه فیلد');
            $table->string('title')->comment('عنوان هشدار');
            $table->text('message')->comment('پیام هشدار');
            $table->enum('alert_type', [
                'info', 'warning', 'error', 'critical'
            ])->comment('نوع هشدار');
            $table->enum('status', [
                'pending', 'sent', 'acknowledged', 'resolved', 'dismissed'
            ])->default('pending')->comment('وضعیت هشدار');
            $table->json('trigger_data')->nullable()->comment('داده‌های فعال‌سازی');
            $table->json('recipients')->nullable()->comment('گیرندگان هشدار');
            $table->datetime('sent_at')->nullable()->comment('زمان ارسال');
            $table->datetime('acknowledged_at')->nullable()->comment('زمان تایید');
            $table->datetime('resolved_at')->nullable()->comment('زمان حل');
            $table->unsignedBigInteger('acknowledged_by')->nullable()->comment('تایید کننده');
            $table->unsignedBigInteger('resolved_by')->nullable()->comment('حل کننده');
            $table->text('resolution_notes')->nullable()->comment('یادداشت‌های حل');
            $table->integer('priority')->default(1)->comment('اولویت');
            $table->boolean('is_read')->default(false)->comment('آیا خوانده شده');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('rule_id')->references('id')->on('rules')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('category_attributes')->onDelete('cascade');
            $table->foreign('acknowledged_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('resolved_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index(['rule_id', 'status']);
            $table->index(['category_id', 'status']);
            $table->index(['alert_type', 'status']);
            $table->index(['priority', 'status']);
            $table->index(['created_at', 'status']);
            $table->index('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
