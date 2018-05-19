@extends('layouts.print')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
        
            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
            
            {{-- @todo refactor date localization to a service --}}
            {{ __('Compilation of') . ' ' . (new Carbon\Carbon($compilation->created_at))->format('d/m/Y') }}

        </div>

        <div class="panel-body">
        
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th colspan="2">
                        <h3>{{ __('Student') }}</h3>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ __('First name') }}</td>
                    <td class="col-sm-4 col-md-4 col-lg-4">{{ $compilation->student->user->first_name }}</td>
                </tr>
                <tr>
                    <td>{{ __('Last name') }}</td>
                    <td class="col-sm-4 col-md-4 col-lg-4">{{ $compilation->student->user->last_name }}</td>
                </tr>
                <tr>
                    <td>{{ __('Identification number') }}</td>
                    <td class="col-sm-4 col-md-4 col-lg-4">{{ $compilation->student->identification_number }}</td>
                </tr>
                </tbody>
            </table>
                
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th colspan="2">
                        <h3>{{ __('Stage') }}</h3>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    
                    <td class="col-sm-8 col-md-8 col-lg-8">{{ __('Location') }}</td>
                    <td class="col-sm-4 col-md-4 col-lg-4">{{ $compilation->stageLocation->name }}</td>
                </tr>
                <tr>
                    
                    <td class="col-sm-8 col-md-8 col-lg-8">{{ __('Ward') }}</td>
                    <td class="col-sm-4 col-md-4 col-lg-4">{{ $compilation->stageWard->name }}</td>
                </tr>
                <tr>
                    
                    <td class="col-sm-8 col-md-8 col-lg-8">{{ __('Period') }}</td>
                    {{-- @todo refactor date localization to a service --}}
                    <td class="col-sm-4 col-md-4 col-lg-4">
                        <span class="hidden-xs">
                        {{ (new Carbon\Carbon($compilation->stage_start_date))->format('d/m/Y') }}
                        -
                        {{ (new Carbon\Carbon($compilation->stage_end_date))->format('d/m/Y') }}
                        </span>
                        <span class="visible-xs-inline hidden-print">
                        {{ (new Carbon\Carbon($compilation->stage_start_date))->format('d/m/y') }}
                        -
                        {{ (new Carbon\Carbon($compilation->stage_end_date))->format('d/m/y') }}
                        </span>
                    </td>
                </tr>
                <tr>
          
                    <td class="col-sm-8 col-md-8 col-lg-8">{{ __('Weeks') }}</td>
                    <td class="col-sm-4 col-md-4 col-lg-4">{{ $compilation->stage_weeks }}</td>
                </tr>
                <tr>
                    <td class="col-sm-8 col-md-8 col-lg-8">
                        <span class="hidden-xs"> {{ __('Academic year') }} </span>
                        <span class="visible-xs-inline hidden-print"> {{ __('Acad. year') }} </span>
                    </td>
                    <td class="col-sm-4 col-md-4 col-lg-4">{{ $compilation->stage_academic_year}}</td>
                </tr>
                </tbody>
            </table>
            
        </div>

    </div>

@endsection
