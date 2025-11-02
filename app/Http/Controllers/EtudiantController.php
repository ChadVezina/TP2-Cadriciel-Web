<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Ville;
use Illuminate\Http\Request;

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
        // associate the student with the currently authenticated user
        // assume authentication is required for this controller/route
        $validatedData['user_id'] = auth()->id();

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
