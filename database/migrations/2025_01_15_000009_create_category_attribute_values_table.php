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
        Schema::create('category_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->comment('شناسه دسته‌بندی');
            $table->unsignedBigInteger('attribute_id')->comment('شناسه فیلد');
            $table->text('value')->nullable()->comment('مقدار فیلد');
            $table->json('json_value')->nullable()->comment('مقدار JSON (برای multiselect)');
            $table->decimal('numeric_value', 15, 4)->nullable()->comment('مقدار عددی');
            $table->date('date_value')->nullable()->comment('مقدار تاریخ');
            $table->datetime('datetime_value')->nullable()->comment('مقدار تاریخ و زمان');
            $table->boolean('boolean_value')->nullable()->comment('مقدار بولی');
            $table->string('file_path')->nullable()->comment('مسیر فایل');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('category_attributes')->onDelete('cascade');
            
            // Unique constraint
            $table->unique(['category_id', 'attribute_id']);
            
            // Indexes
            $table->index(['category_id', 'attribute_id']);
            $table->index('numeric_value');
            $table->index('date_value');
            $table->index('boolean_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_attribute_values');
    }
};
