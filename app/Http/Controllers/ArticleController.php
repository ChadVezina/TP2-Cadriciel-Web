<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Contrôleur ArticleController
 * 
 * Gère les opérations CRUD pour les articles avec support multilingue.
 * Utilise les politiques d'autorisation pour contrôler l'accès aux articles.
 */
class ArticleController extends Controller
{
    /**
     * Crée une nouvelle instance du contrôleur.
     * 
     * @param ArticleService $articleService Service gérant la logique métier des articles
     */
    public function __construct(protected ArticleService $articleService)
    {
    }

    /**
     * Affiche la liste paginée des articles.
     * 
     * @param Request $request Requête HTTP
     * @return View Vue de la liste des articles
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Article::class);

        $articles = $this->articleService->getPaginatedArticles(10);
        $viewLocale = $request->session()->get('article_view_locale', app()->getLocale());
        return view('articles.index', compact('articles', 'viewLocale'));
    }

    /**
     * Affiche le formulaire de création d'un nouvel article.
     * 
     * @return View Vue du formulaire de création
     */
    public function create(): View
    {
        $this->authorize('create', Article::class);

        return view('articles.create');
    }

    /**
     * Enregistre un nouvel article dans la base de données.
     * 
     * @param StoreArticleRequest $request Requête validée contenant les données de l'article
     * @return RedirectResponse Redirection vers la liste des articles avec message de succès
     */
    public function store(StoreArticleRequest $request): RedirectResponse
    {
        $this->authorize('create', Article::class);

        $this->articleService->createArticle($request->validated(), $request->user());

        return redirect()
            ->route('articles.index')
            ->with('success', __('articles.created'));
    }

    /**
     * Affiche un article spécifique.
     * 
     * @param Request $request Requête HTTP
     * @param Article $article Article à afficher
     * @return View Vue de détails de l'article
     */
    public function show(Request $request, Article $article): View
    {
        $this->authorize('view', $article);

        $article = $this->articleService->getArticleWithTranslations($article);
        $viewLocale = $request->session()->get('article_view_locale', app()->getLocale());
        return view('articles.show', compact('article', 'viewLocale'));
    }

    /**
     * Affiche le formulaire de modification d'un article.
     * 
     * @param Article $article Article à modifier
     * @return View Vue du formulaire de modification
     */
    public function edit(Article $article): View
    {
        $this->authorize('update', $article);

        $article->load('translations');
        return view('articles.edit', compact('article'));
    }

    /**
     * Met à jour un article existant dans la base de données.
     * 
     * @param UpdateArticleRequest $request Requête validée contenant les nouvelles données
     * @param Article $article Article à mettre à jour
     * @return RedirectResponse Redirection vers la liste des articles avec message de succès
     */
    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        $this->authorize('update', $article);

        $this->articleService->updateArticle($article, $request->validated());

        return redirect()
            ->route('articles.index')
            ->with('success', __('articles.updated'));
    }

    /**
     * Supprime un article de la base de données.
     * 
     * @param Article $article Article à supprimer
     * @return RedirectResponse Redirection vers la liste des articles avec message de succès
     */
    public function destroy(Article $article): RedirectResponse
    {
        $this->authorize('delete', $article);

        $this->articleService->deleteArticle($article);

        return redirect()
            ->route('articles.index')
            ->with('success', __('articles.deleted'));
    }

    /**
     * Change la langue d'affichage des articles (indépendante de la locale de l'application).
     * 
     * Permet de visualiser les articles dans une langue différente de la langue de l'interface.
     * 
     * @param Request $request Requête HTTP
     * @param string $locale Nouvelle langue d'affichage (fr ou en)
     * @return RedirectResponse Redirection vers la page précédente
     */
    public function changeViewLocale(Request $request, string $locale): RedirectResponse
    {
        if (!in_array($locale, config('app.available_locales') ? array_keys(config('app.available_locales')) : ['fr', 'en'])) {
            abort(400, 'Locale not supported');
        }

        $request->session()->put('article_view_locale', $locale);

        return redirect()->back();
    }
}
