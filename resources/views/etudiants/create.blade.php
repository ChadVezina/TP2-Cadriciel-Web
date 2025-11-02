@extends('layout')

@section('title', 'Nouvel Étudiant')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="mb-4">
            <h1 class="page-title">Ajouter un nouvel étudiant</h1>
            <p class="text-muted">Remplissez les informations de l'étudiant</p>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Erreur de validation :</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('etudiants.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nom complet</label>
                        <input type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Entrez le nom complet"
                            required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-semibold">Adresse</label>
                        <input type="text"
                            class="form-control @error('address') is-invalid @enderror"
                            id="address"
                            name="address"
                            value="{{ old('address') }}"
                            placeholder="Entrez l'adresse"
                            required>
                        @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label fw-semibold">Téléphone</label>
                            <input type="text"
                                class="form-control @error('phone') is-invalid @enderror"
                                id="phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="(555) 123-4567"
                                required>
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="exemple@email.com"
                                required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="birthdate" class="form-label fw-semibold">Date de naissance</label>
                            <input type="date"
                                class="form-control @error('birthdate') is-invalid @enderror"
                                id="birthdate"
                                name="birthdate"
                                value="{{ old('birthdate') }}"
                                required>
                            @error('birthdate')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="city_id" class="form-label fw-semibold">Ville</label>
                            <select class="form-select @error('city_id') is-invalid @enderror"
                                id="city_id"
                                name="city_id"
                                required>
                                <option value="">Sélectionnez une ville</option>
                                @foreach ($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('city_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <a href="{{ route('etudiants.index') }}" class="btn btn-light">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection