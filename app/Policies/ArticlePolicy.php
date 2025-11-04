<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

/**
 * Politique ArticlePolicy
 * 
 * Définit les règles d'autorisation pour les opérations sur les articles.
 * Les articles peuvent être vus par tous, mais seul l'auteur peut les modifier ou supprimer.
 */
class ArticlePolicy
{
    /**
     * Détermine si l'utilisateur peut voir la liste des articles.
     * 
     * @param User|null $user Utilisateur (peut être null pour les invités)
     * @return bool True pour autoriser l'accès à tous
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Détermine si l'utilisateur peut voir un article spécifique.
     * 
     * @param User|null $user Utilisateur (peut être null pour les invités)
     * @param Article $article Article à consulter
     * @return bool True pour autoriser l'accès à tous
     */
    public function view(?User $user, Article $article): bool
    {
        return true;
    }

    /**
     * Détermine si l'utilisateur peut créer un article.
     * 
     * @param User $user Utilisateur authentifié
     * @return bool True si l'utilisateur est authentifié
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour l'article.
     * 
     * Seul l'auteur de l'article peut le modifier.
     * 
     * @param User $user Utilisateur authentifié
     * @param Article $article Article à modifier
     * @return bool True si l'utilisateur est l'auteur
     */
    public function update(User $user, Article $article): bool
    {
        return $user->id === $article->user_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer l'article.
     * 
     * Seul l'auteur de l'article peut le supprimer.
     * 
     * @param User $user Utilisateur authentifié
     * @param Article $article Article à supprimer
     * @return bool True si l'utilisateur est l'auteur
     */
    public function delete(User $user, Article $article): bool
    {
        return $user->id === $article->user_id;
    }

    /**
     * Détermine si l'utilisateur peut restaurer l'article.
     * 
     * @param User $user Utilisateur authentifié
     * @param Article $article Article à restaurer
     * @return bool True si l'utilisateur est l'auteur
     */
    public function restore(User $user, Article $article): bool
    {
        return $user->id === $article->user_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer définitivement l'article.
     * 
     * @param User $user Utilisateur authentifié
     * @param Article $article Article à supprimer définitivement
     * @return bool True si l'utilisateur est l'auteur
     */
    public function forceDelete(User $user, Article $article): bool
    {
        return $user->id === $article->user_id;
    }
}
