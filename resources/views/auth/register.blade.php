@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>

                <div class="panel-body">
                
                        {!! BootForm::open(['route' => 'register', 'method' => 'post']) !!}
                        {{-- http://www.easylaravelbook.com/blog/creating-and-validating-a-laravel-5-form-the-definitive-guide/ --}}
   
                        {!! BootForm::text('first_name', __('First name')) !!}
                        {!! BootForm::text('last_name', __('Last name')) !!}
                        {!! BootForm::email('email', __('E-mail address')) !!}
                        {!! BootForm::password('password', __('Password')) !!}
                        {!! BootForm::password('password_confirmation', __('Confirm password')) !!}

                        {{-- Guest users can only register student users. --}}
                        @if (Auth::guest() === true)
                            {!! BootForm::hidden('role', 'student') !!}
                            {!! BootForm::text('identification_number', __('Identification number')) !!}
                            {!! BootForm::text('gender', __('Gender')) !!}
                            {!! BootForm::text('nationality', __('Nationality')) !!}
                        @endif
                        
                        {{-- Authenticated users can only register viewer users. --}}
                        {{-- @todo find how to access this page when authenticated --}}
                        @if (Auth::guest() === false)
                            {!! BootForm::hidden('role', 'viewer') !!}
                        @endif
                                            
                        {!! BootForm::submit('Register') !!}

                        {!! BootForm::close() !!}
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
          