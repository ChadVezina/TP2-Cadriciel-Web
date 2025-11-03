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
        Schema::create('article_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')
                ->constrained('articles')
                ->onDelete('cascade');
            $table->string('locale', 2); // 'fr' or 'en'
            $table->string('title');
            $table->text('content');
            $table->timestamps();

            // Unique constraint to ensure one translation per language per article
            $table->unique(['article_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_translations');
    }
};
