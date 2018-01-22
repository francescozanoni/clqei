@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <ul>
                            @can('create', App\Models\Compilation::class)
                            <li>{!! link_to_route('compilations.create', __('New compilation')) !!}</li>
                            <li>{!! link_to_route('compilations.index', __('My compilations') . ' (' . Auth::user()->student->compilations->count() . ')') !!}</li>
                            @endcan
                            @can('viewAll', App\Models\Compilation::class)
                            <li>{!! link_to_route('compilations.index', __('All compilations') . ' (' . \App\Models\Compilation::count() . ')') !!}</li>
                            @endcan
                            @can('createViewer', App\User::class)
                            @if (Auth::user()->can('createAdministrator', App\User::class))
                                <li>
                                    {!! link_to_route('register', __('Register new viewer or administrator')) !!}
                                </li>
                            @else
                                <li>
                                    {!! link_to_route('register', __('Register new viewer')) !!}
                                </li>
                            @endif
                            @endcan
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
