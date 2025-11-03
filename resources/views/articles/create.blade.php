@extends('layout')

@section('title', __('Créer un Article'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-plus-circle"></i> {{ __('Créer un Article') }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('articles.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="language" class="form-label">{{ __('Langue') }} principale <span class="text-danger">*</span></label>
                        <select class="form-select @error('language') is-invalid @enderror" 
                                id="language" 
                                name="language" 
                                required>
                            <option value="">{{ __('Sélectionner une langue') }}</option>
                            <option value="fr" {{ old('language', 'fr') == 'fr' ? 'selected' : '' }}>{{ __('Français') }}</option>
                            <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>{{ __('English') }}</option>
                        </select>
                        @error('language')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">{{ __('La langue principale sera affichée par défaut') }}</small>
                    </div>

                    <hr class="my-4">

                    <!-- French Version -->
                    <h5 class="mb-3"><i class="bi bi-translate"></i> {{ __('Version Française') }}</h5>
                    
                    <div class="mb-3">
                        <label for="title_fr" class="form-label">{{ __('Titre en Français') }} <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('title_fr') is-invalid @enderror" 
                               id="title_fr" 
                               name="title_fr" 
                               value="{{ old('title_fr') }}" 
                               required>
                        @error('title_fr')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="content_fr" class="form-label">{{ __('Contenu en Français') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('content_fr') is-invalid @enderror" 
                                  id="content_fr" 
                                  name="content_fr" 
                                  rows="8" 
                                  required>{{ old('content_fr') }}</textarea>
                        @error('content_fr')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <!-- English Version -->
                    <h5 class="mb-3"><i class="bi bi-translate"></i> {{ __('Version Anglaise') }}</h5>
                    
                    <div class="mb-3">
                        <label for="title_en" class="form-label">{{ __('Titre en Anglais') }} <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('title_en') is-invalid @enderror" 
                               id="title_en" 
                               name="title_en" 
                               value="{{ old('title_en') }}" 
                               required>
                        @error('title_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="content_en" class="form-label">{{ __('Contenu en Anglais') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('content_en') is-invalid @enderror" 
                                  id="content_en" 
                                  name="content_en" 
                                  rows="8" 
                                  required>{{ old('content_en') }}</textarea>
                        @error('content_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> {{ __('Publier') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
