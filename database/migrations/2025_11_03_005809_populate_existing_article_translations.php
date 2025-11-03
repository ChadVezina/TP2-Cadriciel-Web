<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Article;
use App\Models\ArticleTranslation;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all existing articles that don't have translations yet
        $articles = Article::doesntHave('translations')->get();

        foreach ($articles as $article) {
            // Create translation for the article's original language
            ArticleTranslation::create([
                'article_id' => $article->id,
                'locale' => $article->language,
                'title' => $article->title,
                'content' => $article->content,
            ]);

            // Create a default translation for the other language
            // (You may want to manually update these later)
            $otherLocale = $article->language === 'fr' ? 'en' : 'fr';
            ArticleTranslation::create([
                'article_id' => $article->id,
                'locale' => $otherLocale,
                'title' => '[Translation needed] ' . $article->title,
                'content' => '[Translation needed] ' . $article->content,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is for data population, no rollback needed
        // If you want to remove these translations, you can delete them manually
    }
};
