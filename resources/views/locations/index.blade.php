@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        {{ __('Stage locations') }}
                    </div>

                    <div class="panel-body">

                        @if ($locations->isEmpty() === false)

                            <table class="table">

                                <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($locations as $location)
                                    <tr>
                                        <td>{{ $location->name }}</td>
                                        <td></td>
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
