@inject('countryService', 'App\Services\CountryService')

@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
            {{ $user->first_name . ' ' . $user->last_name }}
        </div>

        <div class="panel-body">

            <table class="table table-striped table-condensed">
                <thead>

                </thead>
                <tbody>
                <tr>
                    <td>{{ __('First name') }}</td>
                    <td>{{ $user->first_name }}</td>
                </tr>
                <tr>
                    <td>{{ __('Last name') }}</td>
                    <td>{{ $user->last_name }}</td>
                </tr>
                <tr>
                    <td>{{ __('E-mail address') }}</td>
                    <td>{{ $user->email }}</td>
                </tr>
                {{-- Only users allowed to create viewers can see users' role --}}
                @can('createViewer', App\User::class)
                <tr>
                    <td>{{ __('Role') }}</td>
                    <td>{{ __($user->role) }}</td>
                </tr>
                @endcan
                @if ($user->role === \App\User::ROLE_STUDENT)
                    <tr>
                        <td>{{ __('Identification number') }}</td>
                        <td>{{ $user->student->identification_number }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Gender') }}</td>
                        <td>{{ __($user->student->gender) }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Nationality') }}</td>
                        <td>{{ $countryService->getCountries()[$user->student->nationality] }}</td>
                    </tr>
                @endif
                </tbody>
            </table>

        </div>

    </div>

@endsection
