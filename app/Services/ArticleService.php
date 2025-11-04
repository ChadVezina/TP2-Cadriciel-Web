<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticleService
{
    /**
     * Get paginated list of articles.
     */
    public function getPaginatedArticles(int $perPage = 10): LengthAwarePaginator
    {
        return Article::with(['user', 'translations'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Create a new article with translations.
     */
    public function createArticle(array $data, User $user): Article
    {
        $article = Article::create([
            'title' => $data['title_' . $data['language']],
            'content' => $data['content_' . $data['language']],
            'language' => $data['language'],
            'user_id' => $user->id,
        ]);

        $this->syncTranslations($article, $data);

        return $article->fresh(['translations', 'user']);
    }

    /**
     * Update an existing article with translations.
     */
    public function updateArticle(Article $article, array $data): Article
    {
        $article->update([
            'title' => $data['title_' . $data['language']],
            'content' => $data['content_' . $data['language']],
            'language' => $data['language'],
        ]);

        $this->syncTranslations($article, $data);

        return $article->fresh(['translations', 'user']);
    }

    /**
     * Delete an article.
     */
    public function deleteArticle(Article $article): bool
    {
        return $article->delete();
    }

    /**
     * Sync article translations for both languages.
     */
    protected function syncTranslations(Article $article, array $data): void
    {
        $article->translations()->updateOrCreate(
            ['locale' => 'fr'],
            [
                'title' => $data['title_fr'],
                'content' => $data['content_fr'],
            ]
        );

        $article->translations()->updateOrCreate(
            ['locale' => 'en'],
            [
                'title' => $data['title_en'],
                'content' => $data['content_en'],
            ]
        );
    }

    /**
     * Get article with translations.
     */
    public function getArticleWithTranslations(Article $article): Article
    {
        return $article->load('translations', 'user');
    }
}
