@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
            {{-- @todo refactor date localization to a service --}}
            {{ __('Compilation of') . ' ' . (new Carbon\Carbon($compilation->created_at))->format('d/m/Y') }}

            <div class="pull-right hidden-print" onclick="window.print()" style="cursor:pointer">
                &nbsp;
                &nbsp;
                <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
              <span class="hidden-xs">{{ __('Print') }}</span>
            </div>

            <div class="hidden-xs pull-right{{ Auth::user()->can('viewAll', App\Models\Compilation::class) ? '' : ' visible-print-block' }}">
                {{ $compilation->student->user->first_name }}
                {{ $compilation->student->user->last_name }}
                -
                {{ $compilation->student->identification_number }}
            </div>

        </div>

        <div class="panel-body">
        
        @if (Auth::user()->can('viewAll', App\Models\Compilation::class))
            <div class="visible-xs-block">
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                  <th class="col-xs-5">
                  {{ __('Student') }}
                  </th>
                  <th class="col-xs-7"></th>
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
            </div>
        @endif
                
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th colspan="3">{{ __('Stage') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td></td>
                    <td class="col-sm-8 col-md-8 col-lg-8">{{ __('Location') }}</td>
                    <td class="col-sm-4 col-md-4 col-lg-4">{{ $compilation->stageLocation->name }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="col-sm-8 col-md-8 col-lg-8">{{ __('Ward') }}</td>
                    <td class="col-sm-4 col-md-4 col-lg-4">{{ $compilation->stageWard->name }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="col-sm-8 col-md-8 col-lg-8">{{ __('Period') }}</td>
                    {{-- @todo refactor date localization to a service --}}
                    <td class="col-sm-4 col-md-4 col-lg-4">
                        <span class="hidden-xs">
                        {{ (new Carbon\Carbon($compilation->stage_start_date))->format('d/m/Y') }}
                        -
                        {{ (new Carbon\Carbon($compilation->stage_end_date))->format('d/m/Y') }}
                        </span>
                        <span class="visible-xs-inline">
                        {{ (new Carbon\Carbon($compilation->stage_start_date))->format('d/m/y') }}
                        -
                        {{ (new Carbon\Carbon($compilation->stage_end_date))->format('d/m/y') }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td class="col-sm-8 col-md-8 col-lg-8">{{ __('Weeks') }}</td>
                    <td class="col-sm-4 col-md-4 col-lg-4">{{ $compilation->stage_weeks }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="col-sm-8 col-md-8 col-lg-8">
                        <span class="hidden-xs"> {{ __('Academic year') }} </span>
                        <span class="visible-xs-inline"> {{ __('Acad. year') }} </span>
                    </td>
                    <td class="col-sm-4 col-md-4 col-lg-4">{{ $compilation->stage_academic_year}}</td>
                </tr>
                </tbody>
            </table>
            
                @foreach ($compilation->items as $index => $item)
                
                    @if (isset($compilation->items[$index - 1]) === false ||
                        $item->question->section->id !== $compilation->items[$index - 1]->question->section->id)
                         <table class="table table-striped table-condensed">
                         <thead>
                         <tr>
                         <th colspan="3">
                             <span class="hidden-xs">{{ __('Section') }}</span>
                             <span class="visible-xs-inline">{{ __('Sect.') }}</span>
                             {{ $item->question->section->id }}
                             -
                             {{ $item->question->section->title }}
                         </th>
                         </tr>
                         </thead>
                         <tbody>
                    @endif
                    
                    <tr>
                        <td>{{ ($index + 1) }}</td>
                        <td class="col-sm-8 col-md-8 col-lg-8">
                            {{ $item->question->text }}
                        </td>
                        <td class="col-sm-4 col-md-4 col-lg-4">
                            @if (is_array($item->the_answer) === true)
                                @foreach ($item->the_answer as $answer)
                                    <p>{{ $answer->text }}</p>
                                @endforeach
                            @elseif ($item->the_answer !== null)
                                {{ $item->the_answer }}
                            @else

                            @endif
                        </td>
                    </tr>
                    
                    @if (isset($compilation->items[$index + 1]) === false ||
                        $item->question->section->id !== $compilation->items[$index + 1]->question->section->id)
                        </tbody>
                        </table>
                        
                        {{--
                        @todo improve visualization, in order to add section footer
                        @if ($item->question->section->footer !== null)
                            <p>{{ $item->question->section->footer }}</p>
                        @endif
                        --}}
                        
                    @endif
                    
                @endforeach

        </div>

    </div>

@endsection
