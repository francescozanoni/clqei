@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="panel panel-default">

                    <div class="panel-heading">
                        {{ __($panel_title) }}
                    </div>

                    <div class="panel-body">

                        @if ($users->isEmpty() === false)

                            <table class="table">

                                <thead>
                                <tr>
                                    <th>{{ __('First name') }}</th>
                                    <th>{{ __('Last name') }}</th>
                                    <th>{{ __('E-mail address') }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($users as $user)
                                    @can('view', $user)
                                    <tr>
                                        <td>{{ $user->first_name }}</td>
                                        <td>{{ $user->last_name }}</td>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    @endcan
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
