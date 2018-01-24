@inject('compilationService', 'App\Services\CompilationService')

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <div class="panel panel-default">

                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">

                        {{-- @todo add alert about compilations not creatable --}}

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <h3>{{ __('Compilations') }}</h3>
                        <ul>
                            @can('create', App\Models\Compilation::class)
                            @if ($compilationService->isCompilationCreatable() === true)
                                <li>{!! link_to_route('compilations.create', __('New compilation')) !!}</li>
                            @else
                                <li>{{ __('Compilation creation is currently disabled') }}</li>
                            @endif
                            @if ($number_of_compilations > 0)
                                <li>{!! link_to_route('compilations.index', __('My compilations') . ' (' . $number_of_compilations . ')') !!}</li>
                            @endif
                            @endcan
                            @can('viewAll', App\Models\Compilation::class)
                            <li>{!! link_to_route('compilations.index', __('All compilations') . ' (' . $number_of_compilations . ')') !!}</li>
                            @endcan
                        </ul>

                        @can('create', App\Models\Location::class)
                        <h3>{{ __('Stages') }}</h3>
                        <ul>
                            <li>{!! link_to_route('locations.index', __('Sedi')) !!}</li>
                            <li>{!! link_to_route('wards.index', __('Reparti')) !!}</li>
                        </ul>
                        @endcan

                        @can('createViewer', App\User::class)
                        <h3>{{ __('Users') }}</h3>
                        <ul>
                            @if (Auth::user()->can('createAdministrator', App\User::class))
                                <li>{!! link_to_route('register', __('Register new viewer or administrator')) !!}</li>
                            @else
                                <li>{!! link_to_route('register', __('Register new viewer')) !!}</li>
                            @endif
                            @if (Auth::user()->can('createAdministrator', App\User::class))
                                <li>
                                    {!! link_to_route(
                                    'users.index',
                                    __('Administrators') . ' (' . $number_of_administrators . ')',
                                    ['role' => 'administrator']) !!}
                                </li>
                            @endif
                            <li>
                                {!! link_to_route(
                                'users.index',
                                __('Viewers') . ' (' . $number_of_viewers . ')',
                                ['role' => 'viewer']) !!}
                            </li>
                            <li>
                                {!! link_to_route(
                                'users.index',
                                __('Students') . ' (' . $number_of_students . ')',
                                ['role' => 'student']) !!}
                            </li>
                        </ul>
                        @endcan

                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
