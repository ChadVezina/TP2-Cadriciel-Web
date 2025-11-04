@extends('layout')

@section('title', __('students.index.title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title mb-0">{{ __('students.index.title') }}</h1>
    <a href="{{ route('etudiants.create') }}" class="btn btn-primary">
        + {{ __('students.create.new') }}
    </a>
</div>

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="p-3 border-bottom">
            <form method="GET" action="{{ route('etudiants.index') }}" class="row g-2 align-items-center">
                <div class="col-auto">
                    <input type="search" name="search" class="form-control" placeholder="{{ __('students.index.search_placeholder') }}" value="{{ request('search') }}">
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
                        <option value="">{{ __('students.index.name_order') }}</option>
                        <option value="asc" {{ request('name_order') == 'asc' ? 'selected' : '' }}>{{ __('common.sort.az') }}</option>
                        <option value="desc" {{ request('name_order') == 'desc' ? 'selected' : '' }}>{{ __('common.sort.za') }}</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select name="city_order" class="form-select">
                        <option value="">{{ __('students.index.city_order') }}</option>
                        <option value="asc" {{ request('city_order') == 'asc' ? 'selected' : '' }}>{{ __('common.sort.az') }}</option>
                        <option value="desc" {{ request('city_order') == 'desc' ? 'selected' : '' }}>{{ __('common.sort.za') }}</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary">{{ __('common.apply') }}</button>
                    <a href="{{ route('etudiants.index') }}" class="btn btn-link">{{ __('common.reset') }}</a>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 px-4">{{ __('common.name') }}</th>
                        <th class="py-3 px-4">{{ __('common.email') }}</th>
                        <th class="py-3 px-4">{{ __('common.city') }}</th>
                        <th class="py-3 px-4 text-end">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                    <tr>
                        <td class="py-3 px-4 fw-semibold">{{ $student->name }}</td>
                        <td class="py-3 px-4 text-muted">{{ $student->email }}</td>
                        <td class="py-3 px-4">{{ $student->city->name ?? '-' }}</td>
                        <td class="py-3 px-4 text-end">
                            <a href="{{ route('etudiants.show', $student->id) }}"
                                class="btn btn-sm btn-outline-primary">
                                {{ __('common.view') }}
                            </a>
                            <a href="{{ route('etudiants.edit', $student->id) }}"
                                class="btn btn-sm btn-outline-secondary">
                                {{ __('common.edit') }}
                            </a>
                            <form class="d-inline" action="{{ route('etudiants.destroy', $student->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('{{ __('students.delete.confirm') }}');"
                                    type="submit"
                                    class="btn btn-sm btn-outline-danger">
                                    {{ __('common.delete') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            {{ __('students.index.no_students') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@if ($students->hasPages())
    <div class="d-flex justify-content-center mt-3">
        {{ $students->links() }}
    </div>
@endif
@endsection