<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected ArticleService $articleService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Article::class);

        $articles = $this->articleService->getPaginatedArticles(10);
        $viewLocale = $request->session()->get('article_view_locale', app()->getLocale());
        return view('articles.index', compact('articles', 'viewLocale'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Article::class);

        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(Request $request, Article $article): View
    {
        $this->authorize('view', $article);

        $article = $this->articleService->getArticleWithTranslations($article);
        $viewLocale = $request->session()->get('article_view_locale', app()->getLocale());
        return view('articles.show', compact('article', 'viewLocale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article): View
    {
        $this->authorize('update', $article);

        $article->load('translations');
        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
     * Change the view locale for articles (independent from app locale).
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
