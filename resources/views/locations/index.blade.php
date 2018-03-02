@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                @if (session(\App\Observers\ModelObserver::FLASH_MESSAGE_SESSION_KEY))
                    <div class="alert alert-success">
                        {{ session(\App\Observers\ModelObserver::FLASH_MESSAGE_SESSION_KEY) }}
                    </div>
                @endif

                <div class="panel panel-default">

                    <div class="panel-heading">
                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                        {{ __('New location') }}
                    </div>

                    <div class="panel-body">

                        {!! BootForm::inline(['store' => 'locations.store']) !!}

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
                        <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                        {{ __('Stage locations') }}
                    </div>

                    <div class="panel-body">

                        @if ($locations->isEmpty() === false)

                            <table class="table">

                                <thead>
                                </thead>

                                <tbody>
                                @foreach ($locations as $location)
                                    <tr>
                                        <td>
                                            {{ $location->name }}
                                        </td>
                                        <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                            <a href="{{ route('locations.edit', ['location' => $location]) }}"
                                               title="{{ __('Edit') }}">
                                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            </a>
                                        </td>
                                        <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                            {!! BootForm::open(['url' => 'locations/' . $location->id, 'method' => 'delete']) !!}
                                            <a href="{{ route('locations.destroy', ['location' => $location]) }}"
                                               title="{{ __('Delete') }}"
                                               onclick="
                                                       event.preventDefault();
                                                       if (confirm('{{ __('Do you really want to delete this location?') }}') !== true) {
                                                       return;
                                                       }
                                                       this.parentElement.submit();
                                                       ">
                                                 <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                            </a>
                                            {!! BootForm::close() !!}
                                        </td>
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
