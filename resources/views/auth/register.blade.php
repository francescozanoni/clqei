@inject('countryService', 'App\Services\CountryService')

@extends('layouts.app')

@section('content')

    {{-- http://www.easylaravelbook.com/blog/creating-and-validating-a-laravel-5-form-the-definitive-guide/ --}}
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            {{ __('There were some problems registering the user') }}
            <br/>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            @if (Auth::guest() === true)
                {{ __('Register as student') }}
            @elseif (Auth::user()->can('createAdministrator', App\User::class))
                {{ __('Register new viewer or administrator') }}
            @else
                {{ __('Register new viewer') }}
            @endif
        </div>

        <div class="panel-body">

            {!! BootForm::open(['route' => 'register', 'method' => 'post']) !!}

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    {!! BootForm::text('first_name', __('First name')) !!}
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    {!! BootForm::text('last_name', __('Last name')) !!}
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    @if (Auth::guest() === true)
                        {!! BootForm::email(
                        'email',
                        __('University e-mail address')/*,
                        null,
                        // @todo add student e-mail domain
                        ['suffix' => BootForm::addonText(config('clqei.students.email.pattern'))]*/
                        ) !!}
                    @else
                        {!! BootForm::email('email', __('E-mail address')) !!}
                    @endif
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    {!! BootForm::password('password', __('Password')) !!}
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    {!! BootForm::password('password_confirmation', __('Confirm password')) !!}
                </div>
            </div>

            {{-- Guest users can only register student users. --}}
            @if (Auth::guest() === true)
                {!! BootForm::hidden('role', \App\User::ROLE_STUDENT) !!}
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        {!! BootForm::text('identification_number', __('Identification number')) !!}
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        {!! BootForm::select(
                    'nationality',
                    __('Nationality'),
                    $countryService->getCountries(),
                    null,
                    ['placeholder' => __('Select') . '...']
                    ) !!}
                    </div>
                </div>
                {!! BootForm::radios(
                    'gender',
                     __('Gender'),
                     [
                         'male' => __('male'),
                         'female' => __('female')
                     ],
                    null,
                    ['class' => 'radio-inline']
                    ) !!}
            @endif

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

            @if (Auth::guest() === true)
                {!! BootForm::submit(__('Register')) !!}
            @else
                {!! BootForm::submit(__('Register new user')) !!}
            @endif

            {!! BootForm::close() !!}

        </div>
    </div>

@endsection
