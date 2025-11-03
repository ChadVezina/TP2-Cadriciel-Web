<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Ville;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EtudiantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource with optional search, ordering and pagination.
     *
     * Query params supported:
     * - search: string to match against student name (LIKE)
     * - per_page: integer (10..100) items per page
     * - name_order: 'asc' or 'desc' to order by name
     * - city_order: 'asc' or 'desc' to order by city name
     */
    public function index()
    {
        $request = request();

        // pagination size (bounded 10..100)
        $perPage = (int) $request->query('per_page', 10);
        if ($perPage < 10) $perPage = 10;
        if ($perPage > 100) $perPage = 100;

        $search = $request->query('search');
        $nameOrder = $request->query('name_order'); // 'asc'|'desc'|null
        $cityOrder = $request->query('city_order'); // 'asc'|'desc'|null

        $query = Etudiant::with('city')
            ->select('etudiants.*');

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // if requested, order by name
        if (in_array($nameOrder, ['asc', 'desc'])) {
            $query->orderBy('name', $nameOrder);
        }

        // if requested, join cities and order by city name
        if (in_array($cityOrder, ['asc', 'desc'])) {
            $query->leftJoin('villes', 'villes.id', '=', 'etudiants.city_id')
                  ->orderBy('villes.name', $cityOrder)
                  ->select('etudiants.*');
        }

        $students = $query->paginate($perPage)->appends($request->query());

        return view('etudiants.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cities = Ville::orderBy('name')->get();
        return view('etudiants.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|unique:etudiants,email',
            'birthdate' => 'required|date',
            'city_id' => 'required|exists:villes,id',
        ]);
        // Try to associate the student with an existing user matching the
        // email provided in the form. If no user exists, leave the relation null
        // (the migration allows nullable user_id).
            // Find or create a user matching the provided email. If no user exists,
            // create one using the provided name and a random password.
            $user = User::firstOrCreate(
                ['email' => $validatedData['email']],
                [
                    'name' => $validatedData['name'],
                    // use Str::random for a temporary password; User model casts 'password' => 'hashed'
                    'password' => Str::random(12),
                ]
            );
            $validatedData['user_id'] = $user->id;

        $student = Etudiant::create($validatedData);
        return redirect()->route('etudiants.index')->with('success', 'Étudiant créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Etudiant $etudiant)
    {
        $etudiant->load('city');
        return view('etudiants.show', ['student' => $etudiant]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Etudiant $etudiant)
    {
        $cities = Ville::orderBy('name')->get();
        return view('etudiants.edit', compact('etudiant', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Etudiant $etudiant)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|unique:etudiants,email,' . $etudiant->id,
            'birthdate' => 'required|date',
            'city_id' => 'required|exists:villes,id',
        ]);

        // Keep user association consistent with the provided email.
            // On update, ensure the user association follows the (possibly new) email.
            $user = User::firstOrCreate(
                ['email' => $validatedData['email']],
                [
                    'name' => $validatedData['name'],
                    'password' => Str::random(12),
                ]
            );
            $validatedData['user_id'] = $user->id;

        $etudiant->update($validatedData);
        return redirect()->route('etudiants.index')->with('success', 'Étudiant mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Etudiant $etudiant)
    {
        $etudiant->delete();
        return redirect()->route('etudiants.index')->with('success', 'Étudiant supprimé avec succès.');
    }
}
