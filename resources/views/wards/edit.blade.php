@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            {{ __('Edit ward') }}
        </div>

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

@endsection
