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

                    <div class="panel-heading">{{ __('New ward') }}</div>

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
                                        <td>
                                            {!! link_to_route('wards.edit', __('Edit'), ['ward' => $ward]) !!}
                                        </td>
                                        <td>
                                            {!! BootForm::open(['url' => 'wards/' . $ward->id, 'method' => 'delete']) !!}
                                            <a href="{{ route('wards.destroy', ['ward' => $ward]) }}"
                                               onclick="
                                                       event.preventDefault();
                                                       if (confirm('{{ __('Do you really want to delete this ward?') }}') !== true) {
                                                       return;
                                                       }
                                                       this.parentElement.submit();
                                                       ">
                                                {{ __('Delete') }}
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