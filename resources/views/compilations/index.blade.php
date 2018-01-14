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

                            <table class="table">

                                <thead>
                                <tr>
                                    @can('viewAll', App\Models\Compilation::class)
                                    <th>{{ __('Identification number') }}</th>
                                    <th>{{ __('Last name') }}</th>
                                    <th>{{ __('First name') }}</th>
                                    @endcan
                                    <th>{{ __('Date') }}</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($compilations as $compilation)
                                    <tr>
                                        @can('viewAll', App\Models\Compilation::class)
                                        <td>{{ $compilation->student->identification_number }}</td>
                                        <td>{{ $compilation->student->user->last_name }}</td>
                                        <td>{{ $compilation->student->user->first_name }}</td>
                                        @endcan
                                        <td>{{ $compilation->created_at }}</td>
                                        <td>{!! link_to_route('compilations.show', __('View'), ['compilation' => $compilation]) !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>

                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
