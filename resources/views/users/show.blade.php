@extends('layout')
@section('title', 'Détails de l\'utilisateur')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-person-circle"></i> Détails de l'utilisateur</h2>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ $user->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>ID:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $user->id }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Nom:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $user->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $user->email }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Date de création:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $user->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Dernière modification:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $user->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
