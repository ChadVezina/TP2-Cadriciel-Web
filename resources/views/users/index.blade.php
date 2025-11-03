@extends('layout')
@section('title', __('Users List'))
@section('content')
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-people"></i> {{ __('Users List') }}</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> {{ __('New User') }}
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="mb-3">
                <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-center">
                    <div class="col-auto">
                        <input type="search" name="search" class="form-control" placeholder="{{ __('Search name...') }}" value="{{ request('search') }}">
                    </div>
                    <div class="col-auto">
                        <select name="per_page" class="form-select">
                            @foreach([10,25,50,100] as $n)
                                <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="name_order" class="form-select">
                            <option value="">{{ __('Name order') }}</option>
                            <option value="asc" {{ request('name_order') == 'asc' ? 'selected' : '' }}>{{ __('A → Z') }}</option>
                            <option value="desc" {{ request('name_order') == 'desc' ? 'selected' : '' }}>{{ __('Z → A') }}</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="city_order" class="form-select">
                            <option value="">{{ __('City order') }}</option>
                            <option value="asc" {{ request('city_order') == 'asc' ? 'selected' : '' }}>{{ __('A → Z') }}</option>
                            <option value="desc" {{ request('city_order') == 'desc' ? 'selected' : '' }}>{{ __('Z → A') }}</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary">{{ __('Apply') }}</button>
                        <a href="{{ route('users.index') }}" class="btn btn-link">{{ __('Reset') }}</a>
                    </div>
                </form>
            </div>
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('City') }}</th>
                                <th>{{ __('Creation Date') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ optional($user->etudiant)->city->name ?? '-' }}</td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info" title="{{ __('View') }}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning" title="{{ __('Edit') }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="{{ __('Delete') }}"
                                                onclick="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($users->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $users->links() }}
                    </div>
                @endif
            @else
                <p class="text-muted text-center py-4">{{ __('No users found.') }}</p>
            @endif
        </div>
    </div>
@endsection
