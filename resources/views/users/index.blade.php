@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                @if (session(\App\Observers\ModelObserver::FLASH_MESSAGE_SESSION_KEY))
                    <div class="alert alert-success">
                        {{ session(\App\Observers\ModelObserver::FLASH_MESSAGE_SESSION_KEY) }}
                    </div>
                @endif

                <div class="panel panel-default">

                    <div class="panel-heading">
                        <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                        {{ __(ucfirst($user_role ?? 'user') . 's') }}
                    </div>

                    <div class="panel-body">

                        @if ($users === null)

                            <ul>
                                @can('createAdministrator', App\User::class)
                                <li>
                                    <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                                    {{ link_to_route('users.index', __('Administrators'), ['role' => \App\User::ROLE_ADMINISTRATOR]) }}
                                </li>
                                @endcan
                                @can('createViewer', App\User::class)
                                <li>
                                    <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                                    {{ link_to_route('users.index', __('Viewers'), ['role' => \App\User::ROLE_VIEWER]) }}
                                </li>
                                <li>
                                    <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                                    {{ link_to_route('users.index', __('Students'), ['role' => \App\User::ROLE_STUDENT]) }}
                                </li>
                                @endcan
                            </ul>

                        @elseif ($users->isEmpty() === false)

                            <table class="table">

                                <thead>
                                <tr>
                                    @if ($users->first()->role === \App\User::ROLE_STUDENT)
                                        <th>{{ __('Identification number') }}</th>
                                    @endif
                                    <th>{{ __('First name') }}</th>
                                    <th>{{ __('Last name') }}</th>
                                    @if ($users->first()->role !== \App\User::ROLE_STUDENT)
                                        <th class="hidden-xs">{{ __('E-mail address') }}</th>
                                    @endif
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($users as $user)
                                    @can('view', $user)
                                    <tr>
                                        @if ($user->role === \App\User::ROLE_STUDENT)
                                            <td>{{ $user->student->identification_number }}</td>
                                        @endif
                                        <td>{{ $user->first_name }}</td>
                                        <td>{{ $user->last_name }}</td>
                                        @if ($users->first()->role !== \App\User::ROLE_STUDENT)
                                            <td class="hidden-xs">{{ $user->email }}</td>
                                        @endif
                                        <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                            <a href="{{ route('users.show', ['user' => $user]) }}">
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </a>
                                        </td>
                                        <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
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
