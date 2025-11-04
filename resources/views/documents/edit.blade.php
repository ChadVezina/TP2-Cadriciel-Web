@extends('layout')

@section('title', __('Edit Document'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil"></i> {{ __('Edit Document') }}
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('documents.update', $document) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title_fr" class="form-label">
                                {{ __('Title (French)') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('title_fr') is-invalid @enderror"
                                   id="title_fr"
                                   name="title_fr"
                                   value="{{ old('title_fr', $translations['fr']->title ?? '') }}"
                                   required
                                   placeholder="{{ __('Enter document title in French') }}">
                            @error('title_fr')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="title_en" class="form-label">
                                {{ __('Title (English)') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('title_en') is-invalid @enderror"
                                   id="title_en"
                                   name="title_en"
                                   value="{{ old('title_en', $translations['en']->title ?? '') }}"
                                   required
                                   placeholder="{{ __('Enter document title in English') }}">
                            @error('title_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Current File') }}</label>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <i class="bi bi-file-earmark-{{ $document->file_type == 'pdf' ? 'pdf' : ($document->file_type == 'zip' ? 'zip' : 'word') }}-fill text-primary"></i>
                                    <strong>{{ $document->original_filename }}</strong>
                                    <span class="badge bg-secondary ms-2">{{ strtoupper($document->file_type) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">
                                {{ __('Replace File') }} <small class="text-muted">({{ __('optional') }})</small>
                            </label>
                            <input type="file"
                                   class="form-control @error('file') is-invalid @enderror"
                                   id="file"
                                   name="file"
                                   accept=".pdf,.zip,.doc,.docx">
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle"></i>
                                {{ __('Leave empty to keep the current file. Allowed formats: PDF, ZIP, DOC, DOCX. Maximum size: 10 MB') }}
                            </small>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>{{ __('Warning:') }}</strong> {{ __('If you upload a new file, the current file will be permanently deleted.') }}
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> {{ __('Back') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> {{ __('Update Document') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
