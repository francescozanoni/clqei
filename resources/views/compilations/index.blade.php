@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        @if (Auth::user()->can('viewAll', App\Models\Compilation::class))
                            {{ __('All compilations') }}
                        @else
                            {{ __('My compilations') }}
                        @endif
                    </div>

                    <div class="panel-body">

                        @if ($compilations->isEmpty() === false)

                            <ul>
                                @foreach ($compilations as $compilation)
                                    <li>
                                        @can('viewAll', App\Models\Compilation::class)
                                        {{ $compilation->student->identification_number }}
                                        -
                                        {{ $compilation->student->user->last_name }}
                                        {{ $compilation->student->user->first_name }}
                                        -
                                        @endcan
                                        {{ $compilation->created_at }}
                                        -
                                        {!! link_to_route('compilations.show', __('View'), ['compilation' => $compilation]) !!}
                                    </li>
                                @endforeach
                            </ul>

                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
