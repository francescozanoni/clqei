@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
            {{ __('Users') }}
        </div>

        <div class="panel-body">

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
            
        </div>

    </div>

@endsection
