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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            // شناسه و نوع سند
            $table->string('document_number')->unique()->comment('شماره سند');
            $table->string('document_type')->comment('نوع سند: receipt, issue, transfer, return, adjustment');
            $table->date('document_date')->comment('تاریخ سند');

            $table->foreignId('supplier_id')->comment('تامین‌کننده')->nullable()->constrained('suppliers')->nullOnDelete();

            // انبار مبدا و مقصد
            $table->foreignId('source_warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete()->comment('انبار مبدا');
            $table->foreignId('destination_warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete()->comment('انبار مقصد');

            // اطلاعات طرف حساب (برای رسید و صدور)
            $table->string('party_type')->nullable()->comment('نوع طرف: supplier, customer, other');
            $table->string('party_name')->nullable()->comment('نام طرف حساب');
            $table->string('party_code')->nullable()->comment('کد طرف حساب');
            $table->text('party_address')->nullable()->comment('آدرس طرف حساب');
            $table->string('party_phone')->nullable()->comment('تلفن طرف حساب');

            // مرجع و مستندات
            $table->string('reference_number')->nullable()->comment('شماره مرجع/سفارش');
            $table->string('invoice_number')->nullable()->comment('شماره فاکتور');
            $table->date('invoice_date')->nullable()->comment('تاریخ فاکتور');

            // کاربر و تایید
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete()->comment('ثبت کننده');
            $table->foreignId('approver_id')->nullable()->constrained('users')->nullOnDelete()->comment('تایید کننده');
            $table->timestamp('approved_at')->nullable()->comment('تاریخ تایید');

            // وضعیت
            $table->string('status')->default('draft')->comment('وضعیت: draft, approved, cancelled');

            // توضیحات و یادداشت
            $table->text('description')->nullable()->comment('شرح سند');
            $table->json('notes')->nullable()->comment('یادداشت‌ها (Key-Value)');

            // مبالغ کل (محاسبه شده)
            $table->decimal('total_amount', 15, 2)->default(0)->comment('مبلغ کل');
            $table->decimal('tax_amount', 15, 2)->default(0)->comment('مالیات');
            $table->decimal('discount_amount', 15, 2)->default(0)->comment('تخفیف');
            $table->decimal('final_amount', 15, 2)->default(0)->comment('مبلغ نهایی');

            // فایل‌های پیوست
            $table->json('attachments')->nullable()->comment('فایل‌های پیوست');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('document_type');
            $table->index('document_date');
            $table->index('status');
            $table->index('creator_id');
            $table->index('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
