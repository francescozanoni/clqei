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
                        <li>{!! link_to_route('compilations.create', __('Create new compilation')) !!}</li>
                        <li>{!! link_to_route('compilations.index', __('My compilations')) !!}</li>
                    @endcan
                    @can('viewAll', App\Models\Compilation::class)
                        <li>{!! link_to_route('compilations.index', __('All compilations')) !!}</li>
                    @endcan
                    @can('create', App\User::class)
                        <li>{!! link_to_route('register', __('Create viewer')) !!}</li>
                    @endcan
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
