<?php

namespace App\Services;

use App\Models\Etudiant;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

/**
 * Service EtudiantService
 * 
 * Gère la logique métier liée aux étudiants.
 * Fournit des méthodes pour créer, mettre à jour, supprimer et récupérer
 * des étudiants avec filtrage, recherche et tri.
 */
class EtudiantService
{
    /**
     * Récupère une liste paginée d'étudiants avec filtrage et tri optionnels.
     * 
     * Supporte les filtres suivants:
     * - search: Recherche par nom
     * - name_order: Tri par nom (asc/desc)
     * - city_order: Tri par ville (asc/desc)
     * - per_page: Nombre d'éléments par page
     * 
     * @param array $filters Filtres de recherche et tri
     * @return LengthAwarePaginator Liste paginée d'étudiants avec leur ville
     */
    public function getPaginatedStudents(array $filters = []): LengthAwarePaginator
    {
        $perPage = $this->getValidatedPerPage($filters['per_page'] ?? 10);
        $search = $filters['search'] ?? null;
        $nameOrder = $filters['name_order'] ?? null;
        $cityOrder = $filters['city_order'] ?? null;

        $query = Etudiant::with('city')->select('etudiants.*');

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%");
        }

        if (in_array($nameOrder, ['asc', 'desc'])) {
            $query->orderBy('name', $nameOrder);
        }

        if (in_array($cityOrder, ['asc', 'desc'])) {
            $query->leftJoin('villes', 'villes.id', '=', 'etudiants.city_id')
                  ->orderBy('villes.name', $cityOrder)
                  ->select('etudiants.*');
        }

        return $query->paginate($perPage)->appends($filters);
    }

    /**
     * Crée un nouvel étudiant avec un compte utilisateur associé.
     * 
     * Si un utilisateur avec le courriel fourni existe déjà, il est réutilisé.
     * Sinon, un nouveau compte utilisateur est créé automatiquement.
     * 
     * @param array $data Données de l'étudiant incluant email et name
     * @return Etudiant Étudiant créé
     */
    public function createStudent(array $data): Etudiant
    {
        $user = $this->findOrCreateUser($data['email'], $data['name']);
        $data['user_id'] = $user->id;

        return Etudiant::create($data);
    }

    /**
     * Met à jour un étudiant existant.
     * 
     * Le compte utilisateur associé est mis à jour ou créé si nécessaire
     * en fonction du nouvel email fourni.
     * 
     * @param Etudiant $etudiant Étudiant à mettre à jour
     * @param array $data Nouvelles données de l'étudiant
     * @return Etudiant Étudiant mis à jour avec relations rechargées
     */
    public function updateStudent(Etudiant $etudiant, array $data): Etudiant
    {
        $user = $this->findOrCreateUser($data['email'], $data['name']);
        $data['user_id'] = $user->id;

        $etudiant->update($data);
        return $etudiant->fresh();
    }

    /**
     * Supprime un étudiant.
     * 
     * @param Etudiant $etudiant Étudiant à supprimer
     * @return bool True si la suppression a réussi
     */
    public function deleteStudent(Etudiant $etudiant): bool
    {
        return $etudiant->delete();
    }

    /**
     * Trouve un utilisateur existant ou en crée un nouveau par courriel.
     * 
     * Si l'utilisateur existe déjà, il est retourné tel quel.
     * Sinon, un nouveau compte est créé avec un mot de passe aléatoire.
     * 
     * @param string $email Adresse courriel de l'utilisateur
     * @param string $name Nom de l'utilisateur
     * @return User Utilisateur trouvé ou créé
     */
    protected function findOrCreateUser(string $email, string $name): User
    {
        return User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Str::random(12),
            ]
        );
    }

    /**
     * Valide et limite la taille de pagination entre 10 et 100.
     * 
     * @param int $perPage Nombre d'éléments par page demandé
     * @return int Nombre d'éléments par page validé (entre 10 et 100)
     */
    protected function getValidatedPerPage(int $perPage): int
    {
        return max(10, min(100, $perPage));
    }
}
