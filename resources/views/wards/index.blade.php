@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            {{ __('New ward') }}
        </div>

        <div class="panel-body">

            {!! BootForm::inline(['store' => 'wards.store']) !!}

            {{-- http://www.easylaravelbook.com/blog/creating-and-validating-a-laravel-5-form-the-definitive-guide/ --}}
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    {{ __('There were some problems adding the ward') }}
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
            {{ __('Stage wards') }}
        </div>

        <div class="panel-body">

            @if ($wards->isEmpty() === false)

                <table class="table">

                    <thead>
                    </thead>

                    <tbody>
                    @foreach ($wards as $ward)
                        <tr>
                            <td>
                                {{ $ward->name }}
                            </td>
                            <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                <a href="{{ route('wards.edit', ['ward' => $ward]) }}"
                                   title="{{ __('Edit') }}">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                </a>
                            </td>
                            <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                {!! BootForm::open(['url' => 'wards/' . $ward->id, 'method' => 'delete']) !!}
                                <a href="{{ route('wards.destroy', ['ward' => $ward]) }}"
                                   title="{{ __('Delete') }}"
                                   onclick="
                                           event.preventDefault();
                                           if (confirm('{{ __('Do you really want to delete this ward?') }}') !== true) {
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

@endsection
