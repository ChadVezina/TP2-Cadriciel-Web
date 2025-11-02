<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collège Maisonneuve - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/style.css'])
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Collège de Maisonneuve</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('etudiants.index') }}">
                                <i class="bi bi-people-fill"></i> Liste des étudiants
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('etudiants.create') }}">
                                <i class="bi bi-person-plus-fill"></i> Nouvel étudiant
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('users.index') }}">
                                        <i class="bi bi-people"></i> Gérer les utilisateurs
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('auth.destroy') }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Se déconnecter
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Se connecter
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('registration') }}">
                                <i class="bi bi-person-plus"></i> S'inscrire
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>