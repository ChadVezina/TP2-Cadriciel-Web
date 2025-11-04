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

class EtudiantController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected EtudiantService $etudiantService)
    {
    }

    /**
     * Display a listing of the resource with optional search, ordering and pagination.
     *
     * Query params supported:
     * - search: string to match against student name (LIKE)
     * - per_page: integer (10..100) items per page
     * - name_order: 'asc' or 'desc' to order by name
     * - city_order: 'asc' or 'desc' to order by city name
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Etudiant::class);

        $students = $this->etudiantService->getPaginatedStudents($request->query());

        return view('etudiants.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Etudiant::class);

        $cities = Ville::orderBy('name')->get();
        return view('etudiants.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(Etudiant $etudiant): View
    {
        $this->authorize('view', $etudiant);

        $etudiant->load('city', 'user');
        return view('etudiants.show', ['student' => $etudiant]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Etudiant $etudiant): View
    {
        $this->authorize('update', $etudiant);

        $cities = Ville::orderBy('name')->get();
        return view('etudiants.edit', compact('etudiant', 'cities'));
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
