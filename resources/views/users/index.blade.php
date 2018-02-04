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
                                    @if ($users->first()->role === 'student')
                                        <th>{{ __('Identification number') }}</th>
                                    @endif
                                    <th>{{ __('First name') }}</th>
                                    <th>{{ __('Last name') }}</th>
                                    <th class="hidden-xs">{{ __('E-mail address') }}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($users as $user)
                                    @can('view', $user)
                                    <tr>
                                        @if ($user->role === 'student')
                                            <td>{{ $user->student->identification_number }}</td>
                                        @endif
                                        <td>{{ $user->first_name }}</td>
                                        <td>{{ $user->last_name }}</td>
                                        <td class="hidden-xs">{{ $user->email }}</td>
                                        <td>
                                            <a href="{{ route('users.show', ['user' => $user]) }}">
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </a>
                                        </td>
                                        <td>
                                            @can('delete', $user)
                                            {!! BootForm::open(['url' => 'users/' . $user->id, 'method' => 'delete']) !!}
                                            <a href="{{ route('users.destroy', ['user' => $user]) }}"
                                               title="{{ __('Delete') }}"
                                               onclick="
                                                       event.preventDefault();
                                                       if (confirm('{{ __('Do you really want to delete this ' . $user->role . '?') }}') !== true) {
                                                       return;
                                                       }
                                                       this.parentElement.submit();
                                                       ">
                                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>   
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
