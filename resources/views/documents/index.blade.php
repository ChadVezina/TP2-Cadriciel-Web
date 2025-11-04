@extends('layout')

@section('title', __('Document Repository'))

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0">
                    <i class="bi bi-folder-fill"></i> {{ __('Document Repository') }}
                </h1>
                <a href="{{ route('documents.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> {{ __('Share Document') }}
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if($documents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Shared By') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th class="text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                                <tr>
                                    <td>
                                        <i class="bi bi-file-earmark-{{ $document->file_type == 'pdf' ? 'pdf' : ($document->file_type == 'zip' ? 'zip' : 'word') }}-fill text-primary"></i>
                                        <strong>{{ $document->getTitleIn(app()->getLocale()) }}</strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-person-circle"></i> {{ $document->user->name }}
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ strtoupper($document->file_type) }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar"></i>
                                            {{ $document->created_at->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('documents.show', $document) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="{{ __('Download') }}">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            @if($document->isOwnedBy(Auth::user()))
                                                <a href="{{ route('documents.edit', $document) }}"
                                                   class="btn btn-sm btn-outline-secondary"
                                                   title="{{ __('Edit') }}">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="POST"
                                                      action="{{ route('documents.destroy', $document) }}"
                                                      class="d-inline"
                                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this document?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="{{ __('Delete') }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $documents->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-folder-x" style="font-size: 4rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">{{ __('No documents shared yet.') }}</p>
                    <a href="{{ route('documents.create') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-plus-circle"></i> {{ __('Share the first document') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
