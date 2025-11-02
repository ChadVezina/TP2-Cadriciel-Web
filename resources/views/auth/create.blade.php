@extends('layout')
@section('title', 'Connexion')
@section('content')
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row justify-content-center mt-5 mb-5">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">Connexion</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('auth.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Se souvenir de moi
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Se connecter</button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0">Vous n'avez pas de compte? 
                            <a href="{{ route('registration') }}" class="text-decoration-none">S'inscrire</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection