@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        {{ __('Compilation') . ': ' . $compilation->created_at }}
                        @can('viewAll', App\Models\Compilation::class)
                        -
                        {{ $compilation->student->identification_number }}
                        -
                        {{ $compilation->student->user->last_name }}
                        {{ $compilation->student->user->first_name }}
                        @endcan
                    </div>

                    <div class="panel-body">

                        <code>
                        {{ $compilation->toJson() }}
                        </code>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
