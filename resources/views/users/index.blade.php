@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
            {{ __(ucfirst($user_role ?? 'user') . 's') }}
        </div>

        <div class="panel-body">

            @if ($users->isEmpty() === false)

                <table class="table">

                    <thead>
                    <tr>
                        <th>{{ __('First name') }}</th>
                        <th>{{ __('Last name') }}</th>
                        <th class="hidden-xs">{{ __('E-mail address') }}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($users as $user)
                        @can('view', $user)
                        <tr>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td class="hidden-xs">{{ $user->email }}</td>
                            <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                <a href="{{ route('users.show', ['user' => $user]) }}"
                                   title="{{ __('View') }}">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                </a>
                            </td>
                            <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                @can('update', $user)
                                    {!! BootForm::open(['url' => 'users/' . $user->id, 'method' => 'edit']) !!}
                                    <a href="{{ route('users.edit', ['user' => $user]) }}"
                                       title="{{ __('Edit') }}">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    </a>
                                    {!! BootForm::close() !!}
                                @endcan
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

                {{ __('No ' . ($user_role ?? 'user') . 's found') }}

            @endif

        </div>

    </div>

@endsection
