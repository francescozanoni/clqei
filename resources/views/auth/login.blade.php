@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ __('Login') }}</div>

                <div class="panel-body">

                        {!! BootForm::open(['route' => 'login', 'method' => 'post']) !!}
                        {!! BootForm::email('email', __('E-mail address')) !!}
                        {!! BootForm::password('password', __('Password')) !!}
                        {!! BootForm::submit(__('Login')) !!}
                        {!! BootForm::close() !!}

                        {!! link_to_route('password.request', __('Forgot your password?')) !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
