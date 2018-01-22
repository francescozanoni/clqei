@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
            
                <div class="panel panel-default">

                    <div class="panel-heading">{{ __('New location') }}</div>

                    <div class="panel-body">

                        {!! BootForm::open(['route' => 'locations.store', 'method' => 'post']) !!}

                        {{-- http://www.easylaravelbook.com/blog/creating-and-validating-a-laravel-5-form-the-definitive-guide/ --}}
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                {{ __('There were some problems adding the location') }}
                                <br/>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {!! BootForm::text('name', false) !!}

                        {!! BootForm::submit(__('Create')) !!}

                        {!! BootForm::close() !!}

                    </div>
                    
                </div>
            
                <div class="panel panel-default">

                    <div class="panel-heading">
                        {{ __('Stage locations') }}
                    </div>

                    <div class="panel-body">

                        @if ($locations->isEmpty() === false)

                            <table class="table">

                                <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($locations as $location)
                                    <tr>
                                        <td>{{ $location->name }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>

                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
