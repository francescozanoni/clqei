@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>
            {{ __('Login') }}
        </div>

        <div class="panel-body">

            {!! BootForm::open(['route' => 'login', 'method' => 'post']) !!}
            {!! BootForm::email('email', __('E-mail address')) !!}
            {!! BootForm::password('password', __('Password')) !!}
            {!! BootForm::submit(__('Login')) !!}



            {!! BootForm::close() !!}

            <div class="pull-right">
                {!! link_to_route('password.request', __('Forgot your password?')) !!}
            </div>

        </div>

    </div>

@endsection
