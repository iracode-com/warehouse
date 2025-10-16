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
        Schema::table('documents', function (Blueprint $table) {
            // Rename columns
            $table->renameColumn('created_by', 'creator_id');
            $table->renameColumn('approved_by', 'approver_id');

            // Change notes from text to json
            $table->json('notes_new')->nullable()->after('description');
        });

        // Copy data from notes to notes_new
        DB::statement('UPDATE documents SET notes_new = JSON_OBJECT("default", notes) WHERE notes IS NOT NULL');

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('notes');
            $table->renameColumn('notes_new', 'notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Change notes back to text
            $table->text('notes_old')->nullable()->after('description');
        });

        // Copy data back
        DB::statement('UPDATE documents SET notes_old = JSON_UNQUOTE(JSON_EXTRACT(notes, "$.default")) WHERE notes IS NOT NULL');

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('notes');
            $table->renameColumn('notes_old', 'notes');

            // Rename columns back
            $table->renameColumn('creator_id', 'created_by');
            $table->renameColumn('approver_id', 'approved_by');
        });
    }
};
