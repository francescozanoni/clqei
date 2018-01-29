@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="panel-heading">{{ __('Reset password') }}</div>

                    <div class="panel-body">

                        {!! BootForm::open(['route' => 'password.email', 'method' => 'post']) !!}
                        {!! BootForm::email('email', __('E-mail address')) !!}
                        {!! BootForm::submit(__('Send password reset link')) !!}
                        {!! BootForm::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
