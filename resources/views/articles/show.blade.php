@extends('layout')

@section('title', $article->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h2 class="mb-2">{{ $article->title }}</h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person"></i> {{ $article->user->name }} |
                            <i class="bi bi-calendar"></i> {{ $article->created_at->format('d/m/Y H:i') }}
                            @if($article->created_at != $article->updated_at)
                                ({{ __('modifié le') }} {{ $article->updated_at->format('d/m/Y H:i') }})
                            @endif
                            | <span class="badge bg-secondary">{{ strtoupper($article->language) }}</span>
                        </p>
                    </div>
                    @if(Auth::id() === $article->user_id)
                        <div>
                            <a href="{{ route('articles.edit', $article) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> {{ __('Modifier') }}
                            </a>
                            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cet article ?') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> {{ __('Supprimer') }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="article-content">
                    {!! nl2br(e($article->content)) !!}
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> {{ __('Retour au forum') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
