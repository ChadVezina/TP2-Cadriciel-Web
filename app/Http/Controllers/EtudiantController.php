<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEtudiantRequest;
use App\Http\Requests\UpdateEtudiantRequest;
use App\Models\Etudiant;
use App\Models\Ville;
use App\Services\EtudiantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Contrôleur EtudiantController
 * 
 * Gère les opérations CRUD pour les étudiants avec recherche, filtrage et tri.
 * Utilise les politiques d'autorisation pour contrôler l'accès aux profils étudiants.
 */
class EtudiantController extends Controller
{
    /**
     * Crée une nouvelle instance du contrôleur.
     * 
     * @param EtudiantService $etudiantService Service gérant la logique métier des étudiants
     */
    public function __construct(protected EtudiantService $etudiantService)
    {
    }

    /**
     * Affiche la liste paginée des étudiants avec recherche et tri optionnels.
     *
     * Paramètres de requête supportés:
     * - search: Chaîne de caractères pour rechercher par nom (LIKE)
     * - per_page: Nombre d'éléments par page (10 à 100)
     * - name_order: 'asc' ou 'desc' pour trier par nom
     * - city_order: 'asc' ou 'desc' pour trier par nom de ville
     * 
     * @param Request $request Requête HTTP contenant les paramètres de filtrage
     * @return View Vue de la liste des étudiants
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Etudiant::class);

        $students = $this->etudiantService->getPaginatedStudents($request->query());

        return view('etudiants.index', compact('students'));
    }

    /**
     * Affiche le formulaire de création d'un nouvel étudiant.
     * 
     * @return View Vue du formulaire de création avec liste des villes
     */
    public function create(): View
    {
        $this->authorize('create', Etudiant::class);

        $cities = Ville::orderBy('name')->get();
        return view('etudiants.create', compact('cities'));
    }

    /**
     * Enregistre un nouvel étudiant dans la base de données.
     * 
     * @param StoreEtudiantRequest $request Requête validée contenant les données de l'étudiant
     * @return RedirectResponse Redirection vers la liste des étudiants avec message de succès
     */
    public function store(StoreEtudiantRequest $request): RedirectResponse
    {
        $this->authorize('create', Etudiant::class);

        $this->etudiantService->createStudent($request->validated());

        return redirect()
            ->route('etudiants.index')
            ->with('success', __('students.created'));
    }

    /**
     * Affiche les détails d'un étudiant spécifique.
     * 
     * @param Etudiant $etudiant Étudiant à afficher
     * @return View Vue de détails de l'étudiant
     */
    public function show(Etudiant $etudiant): View
    {
        $this->authorize('view', $etudiant);

        $etudiant->load('city', 'user');
        return view('etudiants.show', ['student' => $etudiant]);
    }

    /**
     * Affiche le formulaire de modification d'un étudiant.
     * 
     * @param Etudiant $etudiant Étudiant à modifier
     * @return View Vue du formulaire de modification avec liste des villes
     */
    public function edit(Etudiant $etudiant): View
    {
        $this->authorize('update', $etudiant);

        $cities = Ville::orderBy('name')->get();
        return view('etudiants.edit', compact('etudiant', 'cities'));
    }

    /**
     * Met à jour un étudiant existant dans la base de données.
     * 
     * @param UpdateEtudiantRequest $request Requête validée contenant les nouvelles données
     * @param Etudiant $etudiant Étudiant à mettre à jour
     * @return RedirectResponse Redirection vers la liste des étudiants avec message de succès
     */
    public function update(UpdateEtudiantRequest $request, Etudiant $etudiant): RedirectResponse
    {
        $this->authorize('update', $etudiant);

        $this->etudiantService->updateStudent($etudiant, $request->validated());

        return redirect()
            ->route('etudiants.index')
            ->with('success', __('students.updated'));
    }

    /**
     * Supprime un étudiant de la base de données.
     * 
     * @param Etudiant $etudiant Étudiant à supprimer
     * @return RedirectResponse Redirection vers la liste des étudiants avec message de succès
     */
    public function destroy(Etudiant $etudiant): RedirectResponse
    {
        $this->authorize('delete', $etudiant);

        $this->etudiantService->deleteStudent($etudiant);

        return redirect()
            ->route('etudiants.index')
            ->with('success', __('students.deleted'));
    }
}
