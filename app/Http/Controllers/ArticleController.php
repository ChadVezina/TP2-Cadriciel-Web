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
    public function index(Request $request)
    {
        $articles = Article::with(['user', 'translations'])->latest()->paginate(10);
        $viewLocale = $request->session()->get('article_view_locale', app()->getLocale());
        return view('articles.index', compact('articles', 'viewLocale'));
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
            'title_fr' => 'required|string|max:255',
            'content_fr' => 'required|string',
            'title_en' => 'required|string|max:255',
            'content_en' => 'required|string',
            'language' => 'required|in:fr,en',
        ]);

        // Create the article with the primary language content
        $article = Article::create([
            'title' => $validated['title_' . $validated['language']],
            'content' => $validated['content_' . $validated['language']],
            'language' => $validated['language'],
            'user_id' => Auth::id(),
        ]);

        // Save translations for both languages
        $article->translations()->create([
            'locale' => 'fr',
            'title' => $validated['title_fr'],
            'content' => $validated['content_fr'],
        ]);

        $article->translations()->create([
            'locale' => 'en',
            'title' => $validated['title_en'],
            'content' => $validated['content_en'],
        ]);

        return redirect()->route('articles.index')
            ->with('success', __('Article créé avec succès.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Article $article)
    {
        $article->load('translations');
        $viewLocale = $request->session()->get('article_view_locale', app()->getLocale());
        return view('articles.show', compact('article', 'viewLocale'));
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

        $article->load('translations');
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
            'title_fr' => 'required|string|max:255',
            'content_fr' => 'required|string',
            'title_en' => 'required|string|max:255',
            'content_en' => 'required|string',
            'language' => 'required|in:fr,en',
        ]);

        // Update the article with the primary language content
        $article->update([
            'title' => $validated['title_' . $validated['language']],
            'content' => $validated['content_' . $validated['language']],
            'language' => $validated['language'],
        ]);

        // Update or create translations for both languages
        $article->translations()->updateOrCreate(
            ['locale' => 'fr'],
            [
                'title' => $validated['title_fr'],
                'content' => $validated['content_fr'],
            ]
        );

        $article->translations()->updateOrCreate(
            ['locale' => 'en'],
            [
                'title' => $validated['title_en'],
                'content' => $validated['content_en'],
            ]
        );

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

    /**
     * Change the view locale for articles (independent from app locale)
     */
    public function changeViewLocale(Request $request, string $locale)
    {
        // Validate that the locale is supported
        if (!in_array($locale, ['fr', 'en'])) {
            abort(400, 'Locale not supported');
        }

        // Store article view locale in session
        $request->session()->put('article_view_locale', $locale);

        // Redirect back to previous page
        return redirect()->back();
    }
}
