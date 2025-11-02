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
        Schema::table('etudiants', function (Blueprint $table) {
            // make the relation optional to avoid migration issues on existing data
            // and set the foreign key to NULL if the user is deleted
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etudiants', function (Blueprint $table) {
            // drops the foreign key and the column
            if (Schema::hasColumn('etudiants', 'user_id')) {
                // use dropConstrainedForeignId if available
                if (method_exists($table, 'dropConstrainedForeignId')) {
                    $table->dropConstrainedForeignId('user_id');
                } else {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }
            }
        });
    }
};
