@inject('countryService', 'App\Services\CountryService')

@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
            {{ $user->first_name . ' ' . $user->last_name }}
        </div>

        <div class="panel-body">

            {!! BootForm::open(['model' => $user, 'update' => 'users.update']) !!}

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    {!! BootForm::text('first_name', __('First name')) !!}
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    {!! BootForm::text('last_name', __('Last name')) !!}
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    {!! BootForm::email('email', __('E-mail address')) !!}
                </div>
            </div>

            {{-- Authenticated users can only register viewer users. --}}

            @if (Auth::guest() === false)

                @if (Auth::user()->can('createAdministrator', App\User::class))
                    {!! BootForm::radios(
                   'role',
                    __('Role'),
                    [
                        \App\User::ROLE_VIEWER => __(\App\User::ROLE_VIEWER),
                        \App\User::ROLE_ADMINISTRATOR => __(\App\User::ROLE_ADMINISTRATOR)
                    ]
                   ) !!}
                @else
                    {!! BootForm::hidden('role', \App\User::ROLE_VIEWER) !!}
                @endif

            @endif

            {!! BootForm::submit(__('Save')) !!}

            {!! BootForm::close() !!}

        </div>

    </div>

@endsection
