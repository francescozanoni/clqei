@inject('compilationService', 'App\Services\CompilationService')

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                {{-- @todo add alert about compilations not creatable --}}

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="panel panel-default">

                    <div class="panel-body">

                        <h3>{{ __('Questionnaire compilations') }}</h3>
                        <ul>
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
                            @can('viewAll', App\Models\Compilation::class)
                            <li>
                                <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                                {!! link_to_route('compilations.index', __('All compilations') . ' (' . $number_of_compilations . ')') !!}
                            </li>
                            @endcan
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
                        <ul><li>
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
                                    ['role' => 'administrator']) !!}
                                </li>
                            @endif
                            <li>
                                <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                                {!! link_to_route(
                                'users.index',
                                __('Viewers') . ' (' . $number_of_viewers . ')',
                                ['role' => 'viewer']) !!}
                            </li>
                            <li>
                                <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
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
