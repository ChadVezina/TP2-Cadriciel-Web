@extends('layout')

@section('title', __('documents.edit.title'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil"></i> {{ __('documents.edit.header') }}
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('documents.update', $document) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title_fr" class="form-label">
                                {{ __('documents.form.title_fr') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('title_fr') is-invalid @enderror"
                                   id="title_fr"
                                   name="title_fr"
                                   value="{{ old('title_fr', $translations['fr']->title ?? '') }}"
                                   required
                                   placeholder="{{ __('documents.form.enter_title_fr') }}">
                            @error('title_fr')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="title_en" class="form-label">
                                {{ __('documents.form.title_en') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('title_en') is-invalid @enderror"
                                   id="title_en"
                                   name="title_en"
                                   value="{{ old('title_en', $translations['en']->title ?? '') }}"
                                   required
                                   placeholder="{{ __('documents.form.enter_title_en') }}">
                            @error('title_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('documents.current_file') }}</label>
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
                                {{ __('documents.replace_file') }} <small class="text-muted">({{ __('optional') }})</small>
                            </label>
                            <input type="file"
                                   class="form-control @error('file') is-invalid @enderror"
                                   id="file"
                                   name="file"
                                   accept=".pdf,.zip,.doc,.docx">
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle"></i>
                                {{ __('documents.form.leave_empty') }}
                            </small>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>{{ __('documents.warning.title') }}</strong> {{ __('documents.warning.body') }}
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> {{ __('common.back') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> {{ __('documents.update.button') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
