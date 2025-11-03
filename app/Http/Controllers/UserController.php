<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource with optional search, ordering and pagination.
     *
     * Query params supported:
     * - search: string to match against user name (LIKE)
     * - per_page: integer (10..100) items per page
     * - name_order: 'asc' or 'desc' to order by name
     * - city_order: 'asc' or 'desc' to order by associated student's city name
     */
    public function index()
    {
        $request = request();

        $perPage = (int) $request->query('per_page', 10);
        if ($perPage < 10) $perPage = 10;
        if ($perPage > 100) $perPage = 100;

        $search = $request->query('search');
        $nameOrder = $request->query('name_order'); // 'asc'|'desc'
        $cityOrder = $request->query('city_order'); // 'asc'|'desc'

        $query = User::query()
            // join students and cities to allow ordering by city name while still returning User models
            ->leftJoin('etudiants', 'users.id', '=', 'etudiants.user_id')
            ->leftJoin('villes', 'etudiants.city_id', '=', 'villes.id')
            ->select('users.*')
            ->with('etudiant.city');

        if (!empty($search)) {
            $query->where('users.name', 'like', '%' . $search . '%');
        }

        if (in_array($nameOrder, ['asc', 'desc'])) {
            $query->orderBy('users.name', $nameOrder);
        }

        if (in_array($cityOrder, ['asc', 'desc'])) {
            $query->orderBy('villes.name', $cityOrder);
        }

        $users = $query->paginate($perPage)->appends($request->query());

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|max:20|confirmed'
        ], [
            'name.required' => 'Le nom est requis.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.max' => 'Le mot de passe ne peut pas dépasser 20 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('login')->with('success', 'Votre compte a été créé avec succès! Vous pouvez maintenant vous connecter.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|max:20|confirmed'
        ], [
            'name.required' => 'Le nom est requis.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.max' => 'Le mot de passe ne peut pas dépasser 20 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('users.index')->with('success', 'L\'utilisateur a été modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'L\'utilisateur a été supprimé avec succès.');
    }
}
