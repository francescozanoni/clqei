@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
            {{ __('Students') }}
        </div>

        <div class="panel-body">

            @if ($users->isEmpty() === false)

                <table class="table">

                    <thead>
                    <tr>
                        <th class="hidden-xs">{{ __('Identification number') }}</th>
                        <th>{{ __('First name') }}</th>
                        <th>{{ __('Last name') }}</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($users as $user)
                        @can('view', $user)
                        <tr>
                            <td class="hidden-xs">{{ $user->student->identification_number }}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
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
                                           if (confirm('{{ __('Do you really want to delete this student?') }}') !== true) {
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

                {{ __('No students found') }}

            @endif

        </div>

    </div>

@endsection
