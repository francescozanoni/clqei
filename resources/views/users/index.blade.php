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
                        <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
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
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($users as $user)
                                    @can('view', $user)
                                    <tr>
                                        <td>{{ $user->first_name }}</td>
                                        <td>{{ $user->last_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @can('delete', $user)
                                            {!! BootForm::open(['url' => 'users/' . $user->id, 'method' => 'delete']) !!}
                                            <a href="{{ route('users.destroy', ['user' => $user]) }}"
                                               onclick="
                                                       event.preventDefault();
                                                       if (confirm('{{ __('Do you really want to delete this ' . $user->role . '?') }}') !== true) {
                                                       return;
                                                       }
                                                       this.parentElement.submit();
                                                       ">
                                                {{ __('Delete') }}
                                            </a>
                                            {!! BootForm::close() !!}
                                            @endcan
                                        </td>
                                    </tr>
                                    @endcan
                                @endforeach
                                </tbody>

                            </table>

                        @else

                            {{ __('No ' . strtolower($panel_title) . ' found') }}

                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
