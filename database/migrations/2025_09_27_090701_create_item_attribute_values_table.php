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
        Schema::create('item_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')->comment('شناسه کالا');
            $table->unsignedBigInteger('attribute_id')->comment('شناسه فیلد');
            
            // Value storage columns (only one will be used based on attribute type)
            $table->text('string_value')->nullable()->comment('مقدار متنی');
            $table->decimal('numeric_value', 15, 4)->nullable()->comment('مقدار عددی');
            $table->date('date_value')->nullable()->comment('مقدار تاریخ');
            $table->datetime('datetime_value')->nullable()->comment('مقدار تاریخ و زمان');
            $table->boolean('boolean_value')->nullable()->comment('مقدار بولی');
            $table->json('json_value')->nullable()->comment('مقدار JSON');
            
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('category_attributes')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate attribute values for same item
            $table->unique(['item_id', 'attribute_id'], 'item_attribute_unique');
            
            // Indexes
            $table->index(['item_id', 'attribute_id']);
            $table->index('string_value');
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
        Schema::dropIfExists('item_attribute_values');
    }
};
