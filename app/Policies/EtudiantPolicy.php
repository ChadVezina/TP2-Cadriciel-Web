<?php

namespace App\Policies;

use App\Models\Etudiant;
use App\Models\User;

class EtudiantPolicy
{
    /**
     * Determine whether the user can view any students.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the student.
     */
    public function view(?User $user, Etudiant $etudiant): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create students.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the student.
     */
    public function update(User $user, Etudiant $etudiant): bool
    {
        // Allow if user is admin or if it's their own student profile
        return $user->id === $etudiant->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the student.
     */
    public function delete(User $user, Etudiant $etudiant): bool
    {
        // Allow if user is admin or if it's their own student profile
        return $user->id === $etudiant->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the student.
     */
    public function restore(User $user, Etudiant $etudiant): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the student.
     */
    public function forceDelete(User $user, Etudiant $etudiant): bool
    {
        return $user->isAdmin();
    }
}
