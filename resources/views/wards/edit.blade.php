@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="panel panel-default">

                    <div class="panel-heading">{{ __('Edit ward') }}</div>

                    <div class="panel-body">

                        {!! BootForm::open(['model' => $ward, 'update' => 'wards.update']) !!}

                        {{-- http://www.easylaravelbook.com/blog/creating-and-validating-a-laravel-5-form-the-definitive-guide/ --}}
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                {{ __('There were some problems editing the ward') }}
                                <br/>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {!! BootForm::text('name', false) !!}

                        {!! BootForm::submit(__('Edit')) !!}

                        {!! BootForm::close() !!}

                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
