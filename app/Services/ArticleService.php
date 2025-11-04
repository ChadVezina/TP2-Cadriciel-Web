<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Service ArticleService
 * 
 * Gère la logique métier liée aux articles.
 * Fournit des méthodes pour créer, mettre à jour, supprimer et récupérer
 * des articles avec leur gestion multilingue.
 */
class ArticleService
{
    /**
     * Récupère une liste paginée d'articles avec leurs traductions.
     * 
     * @param int $perPage Nombre d'articles par page (défaut: 10)
     * @return LengthAwarePaginator Liste paginée d'articles avec utilisateur et traductions
     */
    public function getPaginatedArticles(int $perPage = 10): LengthAwarePaginator
    {
        return Article::with(['user', 'translations'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Crée un nouvel article avec ses traductions.
     * 
     * L'article est créé dans sa langue principale, puis les traductions
     * en français et anglais sont synchronisées.
     * 
     * @param array $data Données de l'article incluant les traductions
     * @param User $user Utilisateur auteur de l'article
     * @return Article Article créé avec traductions et utilisateur chargés
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
     * Met à jour un article existant avec ses traductions.
     * 
     * @param Article $article Article à mettre à jour
     * @param array $data Nouvelles données de l'article incluant les traductions
     * @return Article Article mis à jour avec traductions et utilisateur chargés
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
     * Supprime un article et ses traductions.
     * 
     * @param Article $article Article à supprimer
     * @return bool True si la suppression a réussi
     */
    public function deleteArticle(Article $article): bool
    {
        return $article->delete();
    }

    /**
     * Synchronise les traductions d'un article dans les deux langues.
     * 
     * Crée ou met à jour les traductions en français et en anglais.
     * 
     * @param Article $article Article dont les traductions doivent être synchronisées
     * @param array $data Données contenant les traductions (title_fr, content_fr, title_en, content_en)
     * @return void
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
     * Récupère un article avec ses traductions et son utilisateur.
     * 
     * @param Article $article Article à charger
     * @return Article Article avec relations chargées
     */
    public function getArticleWithTranslations(Article $article): Article
    {
        return $article->load('translations', 'user');
    }
}
