<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Document;
use App\Models\Etudiant;
use App\Policies\ArticlePolicy;
use App\Policies\DocumentPolicy;
use App\Policies\EtudiantPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Article::class => ArticlePolicy::class,
        Document::class => DocumentPolicy::class,
        Etudiant::class => EtudiantPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force Bootstrap 5 pagination
        Paginator::useBootstrapFive();

        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}

