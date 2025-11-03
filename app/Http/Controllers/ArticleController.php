<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::with('user')->latest()->paginate(10);
        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'language' => 'required|in:fr,en',
        ]);

        $validated['user_id'] = Auth::id();

        Article::create($validated);

        return redirect()->route('articles.index')
            ->with('success', __('Article créé avec succès.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        // Vérifier que l'utilisateur est l'auteur de l'article
        if ($article->user_id !== Auth::id()) {
            abort(403, __('Vous n\'êtes pas autorisé à modifier cet article.'));
        }

        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        // Vérifier que l'utilisateur est l'auteur de l'article
        if ($article->user_id !== Auth::id()) {
            abort(403, __('Vous n\'êtes pas autorisé à modifier cet article.'));
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'language' => 'required|in:fr,en',
        ]);

        $article->update($validated);

        return redirect()->route('articles.index')
            ->with('success', __('Article modifié avec succès.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        // Vérifier que l'utilisateur est l'auteur de l'article
        if ($article->user_id !== Auth::id()) {
            abort(403, __('Vous n\'êtes pas autorisé à supprimer cet article.'));
        }

        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', __('Article supprimé avec succès.'));
    }
}
