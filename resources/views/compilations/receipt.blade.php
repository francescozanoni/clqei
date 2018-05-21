@extends('layouts.print')

@section('content')

    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        <h1 style="margin-top: 1mm">
            {{ config('app.name') }}
            <br />
            <small>{{ config('app.name_extended') }}</small>
        </h1>
    </div>
    
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        @if (file_exists(public_path('logo.svg')) === true)
            <img id="logo" src="{{ asset('logo.svg') }}" class="img-responsive" style="width: 100% \9"/>
        @elseif(file_exists(public_path('logo.png')) === true)
            <img id="logo" src="{{ asset('logo.png') }}" class="img-responsive" style="width: 100% \9"/>
        @elseif(file_exists(public_path('logo.jpg')) === true)
            <img id="logo" src="{{ asset('logo.jpg') }}" class="img-responsive" style="width: 100% \9"/>
        @endif
    </div>
    
    <div class="clearfix visible-xs-block visible-sm-block visible-md-block visible-lg-block">
        <hr />
        <br /><br /><br />
    </div>
    
    <div class="panel panel-default col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">

        <div class="panel-heading">
        
            <h3>
            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
            
            {{-- @todo refactor date localization to a service --}}
            {{ __('Compilation of') . ' ' . (new Carbon\Carbon($compilation->created_at))->format('d/m/Y') }}
            </h3>

        </div>

        <div class="panel-body">
        
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <h3>{{ __('Student') }}</h3>
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ __('First name') }}</td>
                    <td>{{ $compilation->student->user->first_name }}</td>
                </tr>
                <tr>
                    <td>{{ __('Last name') }}</td>
                    <td>{{ $compilation->student->user->last_name }}</td>
                </tr>
                <tr>
                    <td>{{ __('Identification number') }}</td>
                    <td>{{ $compilation->student->identification_number }}</td>
                </tr>
                </tbody>
            </table>
                
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <h3>{{ __('Stage') }}</h3>
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ __('Location') }}</td>
                    <td>{{ $compilation->stageLocation->name }}</td>
                </tr>
                <tr>
                    <td>{{ __('Ward') }}</td>
                    <td>{{ $compilation->stageWard->name }}</td>
                </tr>
                <tr>
                    <td>{{ __('Period') }}</td>
                    {{-- @todo refactor date localization to a service --}}
                    <td>
                        {{ (new Carbon\Carbon($compilation->stage_start_date))->format('d/m/Y') }}
                        -
                        {{ (new Carbon\Carbon($compilation->stage_end_date))->format('d/m/Y') }}
                    </td>
                </tr>
                <tr>
                    <td>{{ __('Academic year') }}</td>
                    <td>{{ $compilation->stage_academic_year}}</td>
                </tr>
                </tbody>
            </table>
            
        </div>
        
        <div class="panel-footer">
        
            <div class="text-center">
                {!! QrCode::size(200)->generate(Request::url()); !!}
                <p>{!! Request::url(); !!}</p>
            </div>
        
        </div>

    </div>

@endsection
