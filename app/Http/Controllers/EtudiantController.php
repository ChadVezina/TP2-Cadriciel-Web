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
    public function index()
    {
        $students = Etudiant::with('city')->orderBy('name')->get();
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
