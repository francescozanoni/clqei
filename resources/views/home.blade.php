@inject('compilationService', 'App\Services\CompilationService')

@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
            Home
        </div>

        <div class="panel-body">

            <h3>{{ __('Questionnaire compilations') }}</h3>
            <ul>
                {{-- Student --}}
                @can('create', App\Models\Compilation::class)
                @if ($compilationService->isCompilationCreatable() === true)
                    <li>
                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                        {!! link_to_route('compilations.create', __('New compilation')) !!}
                    </li>
                @else
                    <li>{{ __('Compilation creation is currently disabled') }}</li>
                @endif
                @if ($number_of_compilations > 0)
                    <li>
                        <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                        {!! link_to_route('compilations.index', __('My compilations') . ' (' . $number_of_compilations . ')') !!}
                    </li>
                @endif
                @endcan
                {{-- Viewer/administrator --}}
                @cannot('create', App\Models\Compilation::class)
                @if ($compilationService->isCompilationCreatable() === true)
                    <li>
                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                        {!! link_to_route('compilations.create', __('Compilation form')) !!}
                    </li>
                @else
                    <li>{{ __('Compilation creation is currently disabled') }}</li>
                @endif
                @endcannot
                @if ($number_of_compilations > 0)
                @can('viewAll', App\Models\Compilation::class)
                <li>
                    <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                    {!! link_to_route('compilations.index', __('All compilations') . ' (' . $number_of_compilations . ')') !!}
                </li>
                @endcan
                {{-- Compilation statistics temporarily accessible only to administrators --}}
                @if (Auth::user()->can('createAdministrator', App\User::class))
                    <li>
                        <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
                        {!! link_to_route('compilations.statistics', __('Statistics')) !!}
                    </li>
                @endif
                @endif
            </ul>

            @can('create', App\Models\Location::class)
            <h3>{{ __('Stages') }}</h3>
            <ul>
                <li>
                    <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                    {!! link_to_route('locations.index', __('Locations') . ' (' . $number_of_locations . ')') !!}
                </li>
                <li>
                    <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                    {!! link_to_route('wards.index', __('Wards') . ' (' . $number_of_wards . ')') !!}
                </li>
            </ul>
            @endcan

            @can('createViewer', App\User::class)
            <h3>{{ __('Users') }}</h3>
            <ul>
                <li>
                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                    @if (Auth::user()->can('createAdministrator', App\User::class))
                        {!! link_to_route('register', __('Register new viewer or administrator')) !!}
                    @else
                        {!! link_to_route('register', __('Register new viewer')) !!}
                    @endif
                </li>
                @if (Auth::user()->can('createAdministrator', App\User::class))
                    <li>
                        <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                        {!! link_to_route(
                        'users.index',
                        __('Administrators') . ' (' . $number_of_administrators . ')',
                        ['role' => \App\User::ROLE_ADMINISTRATOR]) !!}
                    </li>
                @endif
                <li>
                    <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                    {!! link_to_route(
                    'users.index',
                    __('Viewers') . ' (' . $number_of_viewers . ')',
                    ['role' => \App\User::ROLE_VIEWER]) !!}
                </li>
                <li>
                    <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                    {!! link_to_route(
                    'users.index',
                    __('Students') . ' (' . $number_of_students . ')',
                    ['role' => \App\User::ROLE_STUDENT]) !!}
                </li>
            </ul>
            @endcan

        </div>

    </div>

@endsection
