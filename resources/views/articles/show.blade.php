@extends('layout')

@section('title', $article->getTitleIn($viewLocale))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h2 class="mb-2">
                            {{ $article->getTitleIn($viewLocale) }}
                            @if($article->isFullyTranslated())
                                <span class="badge bg-success ms-2">
                                    <i class="bi bi-check-circle"></i> {{ __('articles.status.translated') }}
                                </span>
                            @else
                                <span class="badge bg-warning text-dark ms-2">
                                    <i class="bi bi-exclamation-circle"></i> {{ __('articles.status.not_translated') }}
                                </span>
                            @endif
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person"></i> {{ $article->user->name }} |
                            <i class="bi bi-calendar"></i> {{ $article->created_at->format('d/m/Y H:i') }}
                                @if($article->created_at != $article->updated_at)
                                ({{ __('articles.modified_on') }} {{ $article->updated_at->format('d/m/Y H:i') }})
                            @endif
                            | <span class="badge bg-secondary">{{ strtoupper($article->language) }}</span>
                        </p>
                    </div>
                    @if(Auth::id() === $article->user_id)
                        <div>
                            <a href="{{ route('articles.edit', $article) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> {{ __('common.edit') }}
                            </a>
                            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('{{ __('articles.delete.confirm') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> {{ __('common.delete') }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                <!-- Language Toggle Buttons for Article -->
                <div class="d-flex justify-content-center">
                    <div class="btn-group" role="group">
                        <a href="{{ route('articles.viewlocale.change', 'fr') }}" 
                           class="btn btn-sm {{ $viewLocale == 'fr' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-translate"></i> {{ __('articles.actions.view_fr') }}
                        </a>
                        <a href="{{ route('articles.viewlocale.change', 'en') }}" 
                           class="btn btn-sm {{ $viewLocale == 'en' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-translate"></i> {{ __('articles.actions.view_en') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="article-content">
                    {!! nl2br(e($article->getContentIn($viewLocale))) !!}
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> {{ __('articles.back_to_forum') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
