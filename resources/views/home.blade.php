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

                    @can('create', App\Models\Compilation::class)
                        {!! link_to_route('compilations.create', __('Create new compilation')) !!}
                        <br />
                        {!! link_to_route('compilations.index', __('View your compilations')) !!}
                    @endcan
                    @can('viewAll', App\Models\Compilation::class)
                        {!! link_to_route('compilations.index', __('View all compilations')) !!}
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
