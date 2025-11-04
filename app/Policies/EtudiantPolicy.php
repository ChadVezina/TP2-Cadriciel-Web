<?php

namespace App\Policies;

use App\Models\Etudiant;
use App\Models\User;

/**
 * Politique EtudiantPolicy
 * 
 * Définit les règles d'autorisation pour les opérations sur les profils étudiants.
 * Les profils peuvent être vus par tous, mais seuls le propriétaire et les administrateurs
 * peuvent les modifier ou supprimer.
 */
class EtudiantPolicy
{
    /**
     * Détermine si l'utilisateur peut voir la liste des étudiants.
     * 
     * @param User|null $user Utilisateur (peut être null pour les invités)
     * @return bool True pour autoriser l'accès à tous
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Détermine si l'utilisateur peut voir un profil étudiant spécifique.
     * 
     * @param User|null $user Utilisateur (peut être null pour les invités)
     * @param Etudiant $etudiant Étudiant à consulter
     * @return bool True pour autoriser l'accès à tous
     */
    public function view(?User $user, Etudiant $etudiant): bool
    {
        return true;
    }

    /**
     * Détermine si l'utilisateur peut créer un profil étudiant.
     * 
     * @param User $user Utilisateur authentifié
     * @return bool True si l'utilisateur est authentifié
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour le profil étudiant.
     * 
     * Autorisé si l'utilisateur est administrateur ou si c'est son propre profil.
     * 
     * @param User $user Utilisateur authentifié
     * @param Etudiant $etudiant Étudiant à modifier
     * @return bool True si l'utilisateur est le propriétaire ou administrateur
     */
    public function update(User $user, Etudiant $etudiant): bool
    {
        return $user->id === $etudiant->user_id || $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut supprimer le profil étudiant.
     * 
     * Autorisé si l'utilisateur est administrateur ou si c'est son propre profil.
     * 
     * @param User $user Utilisateur authentifié
     * @param Etudiant $etudiant Étudiant à supprimer
     * @return bool True si l'utilisateur est le propriétaire ou administrateur
     */
    public function delete(User $user, Etudiant $etudiant): bool
    {
        return $user->id === $etudiant->user_id || $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut restaurer le profil étudiant.
     * 
     * @param User $user Utilisateur authentifié
     * @param Etudiant $etudiant Étudiant à restaurer
     * @return bool True si l'utilisateur est administrateur
     */
    public function restore(User $user, Etudiant $etudiant): bool
    {
        return $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut supprimer définitivement le profil étudiant.
     * 
     * @param User $user Utilisateur authentifié
     * @param Etudiant $etudiant Étudiant à supprimer définitivement
     * @return bool True si l'utilisateur est administrateur
     */
    public function forceDelete(User $user, Etudiant $etudiant): bool
    {
        return $user->isAdmin();
    }
}
