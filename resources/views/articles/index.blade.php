@extends('layout')

@section('title', __('articles.index.title'))

@section('content')
<div class="row">
    <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-chat-left-text"></i> {{ __('articles.index.title') }}</h1>
            <div class="d-flex gap-2">
                <!-- Language Toggle Buttons for Articles -->
                <div class="btn-group" role="group">
                    <a href="{{ route('articles.viewlocale.change', 'fr') }}" 
                       class="btn btn-sm {{ $viewLocale == 'fr' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="bi bi-translate"></i> {{ __('FR') }}
                    </a>
                    <a href="{{ route('articles.viewlocale.change', 'en') }}" 
                       class="btn btn-sm {{ $viewLocale == 'en' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="bi bi-translate"></i> {{ __('EN') }}
                    </a>
                </div>
                <a href="{{ route('articles.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> {{ __('articles.index.new') }}
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

                @if($articles->isEmpty())
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> {{ __('articles.empty.no_articles') }}
            </div>
        @else
            <div class="row">
                @foreach($articles as $article)
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                                        {{ $article->getTitleIn($viewLocale) }}
                                    </a>
                                    <span class="badge bg-secondary ms-2">{{ strtoupper($article->language) }}</span>
                                    @if($article->isFullyTranslated())
                                        <span class="badge bg-success ms-1">
                                            <i class="bi bi-check-circle"></i> {{ __('articles.status.translated') }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark ms-1">
                                            <i class="bi bi-exclamation-circle"></i> {{ __('articles.status.not_translated') }}
                                        </span>
                                    @endif
                                </h5>
                                <small class="text-muted">
                                    <i class="bi bi-person"></i> {{ $article->user->name }} |
                                    <i class="bi bi-calendar"></i> {{ $article->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{ Str::limit($article->getContentIn($viewLocale), 200) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> {{ __('articles.actions.read_more') }}
                                    </a>
                                    @if(Auth::id() === $article->user_id)
                                        <div>
                                            <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i> {{ __('common.edit') }}
                                            </a>
                                            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline" 
                                                  onsubmit="return confirm('{{ __('articles.delete.confirm') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i> {{ __('common.delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
