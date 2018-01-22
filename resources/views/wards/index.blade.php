@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        {{ __('Stage wards') }}
                    </div>

                    <div class="panel-body">

                        @if ($wards->isEmpty() === false)

                            <table class="table">

                                <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($wards as $ward)
                                    <tr>
                                        <td>{{ $ward->name }}</td>
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
