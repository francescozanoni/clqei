@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            {{ __('Reset password') }}
        </div>

        <div class="panel-body">

            {!! BootForm::open(['route' => 'password.email', 'method' => 'post']) !!}
            {!! BootForm::email('email', __('E-mail address')) !!}
            {!! BootForm::submit(__('Send reset link')) !!}
            {!! BootForm::close() !!}

        </div>

    </div>

@endsection
