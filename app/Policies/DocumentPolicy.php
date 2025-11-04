<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

/**
 * Politique DocumentPolicy
 * 
 * Définit les règles d'autorisation pour les opérations sur les documents.
 * Les documents peuvent être vus et téléchargés par tous les utilisateurs authentifiés,
 * mais seul le propriétaire peut les modifier ou supprimer.
 */
class DocumentPolicy
{
    /**
     * Détermine si l'utilisateur peut voir la liste des documents.
     * 
     * @param User $user Utilisateur authentifié
     * @return bool True pour autoriser l'accès à tous les utilisateurs authentifiés
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Détermine si l'utilisateur peut voir un document spécifique.
     * 
     * @param User $user Utilisateur authentifié
     * @param Document $document Document à consulter
     * @return bool True pour autoriser l'accès à tous les utilisateurs authentifiés
     */
    public function view(User $user, Document $document): bool
    {
        return true;
    }

    /**
     * Détermine si l'utilisateur peut créer un document.
     * 
     * @param User $user Utilisateur authentifié
     * @return bool True si l'utilisateur est authentifié
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour le document.
     * 
     * Seul le propriétaire du document peut le modifier.
     * 
     * @param User $user Utilisateur authentifié
     * @param Document $document Document à modifier
     * @return bool True si l'utilisateur est le propriétaire
     */
    public function update(User $user, Document $document): bool
    {
        return $user->id === $document->user_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer le document.
     * 
     * Seul le propriétaire du document peut le supprimer.
     * 
     * @param User $user Utilisateur authentifié
     * @param Document $document Document à supprimer
     * @return bool True si l'utilisateur est le propriétaire
     */
    public function delete(User $user, Document $document): bool
    {
        return $user->id === $document->user_id;
    }

    /**
     * Détermine si l'utilisateur peut restaurer le document.
     * 
     * @param User $user Utilisateur authentifié
     * @param Document $document Document à restaurer
     * @return bool True si l'utilisateur est le propriétaire
     */
    public function restore(User $user, Document $document): bool
    {
        return $user->id === $document->user_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer définitivement le document.
     * 
     * @param User $user Utilisateur authentifié
     * @param Document $document Document à supprimer définitivement
     * @return bool True si l'utilisateur est le propriétaire
     */
    public function forceDelete(User $user, Document $document): bool
    {
        return $user->id === $document->user_id;
    }

    /**
     * Détermine si l'utilisateur peut télécharger le document.
     * 
     * @param User $user Utilisateur authentifié
     * @param Document $document Document à télécharger
     * @return bool True pour autoriser le téléchargement à tous les utilisateurs authentifiés
     */
    public function download(User $user, Document $document): bool
    {
        return true;
    }
}
