@extends('layout')

@section('title', __('Modifier l\'Article'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-pencil"></i> {{ __('Modifier l\'Article') }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('articles.update', $article) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('Titre') }} <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $article->title) }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="language" class="form-label">{{ __('Langue') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('language') is-invalid @enderror" 
                                id="language" 
                                name="language" 
                                required>
                            <option value="">{{ __('Sélectionner une langue') }}</option>
                            <option value="fr" {{ old('language', $article->language) == 'fr' ? 'selected' : '' }}>{{ __('Français') }}</option>
                            <option value="en" {{ old('language', $article->language) == 'en' ? 'selected' : '' }}>{{ __('English') }}</option>
                        </select>
                        @error('language')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">{{ __('Contenu') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" 
                                  name="content" 
                                  rows="10" 
                                  required>{{ old('content', $article->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('articles.show', $article) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> {{ __('Annuler') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> {{ __('Enregistrer') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
