@extends('layout')

@section('title', __('documents.create.title'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-upload"></i> {{ __('documents.create.header') }}
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title_fr" class="form-label">
                                {{ __('documents.form.title_fr') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('title_fr') is-invalid @enderror"
                                   id="title_fr"
                                   name="title_fr"
                                   value="{{ old('title_fr') }}"
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
                                   value="{{ old('title_en') }}"
                                   required
                                   placeholder="{{ __('documents.form.enter_title_en') }}">
                            @error('title_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">
                                {{ __('documents.form.file') }} <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                   class="form-control @error('file') is-invalid @enderror"
                                   id="file"
                                   name="file"
                                   required
                                   accept=".pdf,.zip,.doc,.docx">
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle"></i>
                                {{ __('documents.form.allowed_formats') }}
                            </small>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-lightbulb"></i>
                            <strong>{{ __('documents.note.title') }}</strong> {{ __('documents.note.body') }}
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> {{ __('common.back') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload"></i> {{ __('documents.create.submit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
