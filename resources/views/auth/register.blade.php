@inject('countryService', 'App\Services\CountryService')

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                  @if (Auth::guest() === true)
                      {{ __('Register new student') }}
                  @else
                      @can('createAdministrator', App\User::class)
                          {{ __('Register new viewer or administrator') }}
                      @elsecan
                          {{ __('Register new viewer') }}
                      @endcan
                  @endif
                </div>

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
                            {!! BootForm::radios(
                                'gender',
                                 __('Gender'),
                                 [
                                     'male' => __('male'),
                                     'female' => __('female')
                                 ]
                                ) !!}
                            {!! BootForm::select(
                                'nationality',
                                __('Nationality'),
                                $countryService->getCountries(),
                                null,
                                ['placeholder' => __('Select') . '...']
                                ) !!}
                        @endif
                        
                        {{-- Authenticated users can only register viewer users. --}}
                        
                        @if (Auth::guest() === false)
                        @can('createAdministrator', App\User::class)
                                         {!! BootForm::radios(
                                'role',
                                 __('Role'),
                                 [
                                     'viewer' => __('viewer'),
                                     'administrator' => __('administrator')
                                 ]
                                ) !!}
@else
{!! BootForm::hidden('role', 'viewer') !!}
                                
                                @endcan
                           
                        @endif
                                            
                        {!! BootForm::submit('Register') !!}

                        {!! BootForm::close() !!}
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
          