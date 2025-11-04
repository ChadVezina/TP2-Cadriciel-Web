@extends('layout')

@section('title', __('students.show.title'))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="mb-4">
            <h1 class="page-title">{{ __('students.show.title') }}</h1>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h2 class="h4 mb-0">{{ $student->name }}</h2>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block mb-1">{{ __('common.email') }}</small>
                            <div class="fw-semibold">{{ $student->email }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block mb-1">{{ __('common.phone') }}</small>
                            <div class="fw-semibold">{{ $student->phone }}</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block mb-1">{{ __('common.address') }}</small>
                            <div class="fw-semibold">{{ $student->address }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block mb-1">{{ __('common.date_of_birth') }}</small>
                            <div class="fw-semibold">{{ $student->birthdate }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block mb-1">{{ __('common.city') }}</small>
                            <div class="fw-semibold">{{ $student->city->name ?? __('common.not_specified') }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('etudiants.edit', $student) }}" class="btn btn-primary">{{ __('common.edit') }}</a>
                    <a href="{{ route('etudiants.index') }}" class="btn btn-light">{{ __('students.index.title') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection