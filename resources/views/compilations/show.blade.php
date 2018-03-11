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
                
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th class="col-xs-5">{{ __('Stage') }}</th>
                    <th class="col-sm-4 col-md-4 col-lg-4"></th>
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
                    <td>{{ __('Weeks') }}</td>
                    <td>{{ $compilation->stage_weeks }}</td>
                </tr>
                <tr>
                    <td>
                    <span class="hidden-xs"> {{ __('Academic year') }} </span>
                    <span class="visible-xs-inline"> {{ __('A. Y.') }} </span>
                   
                    </td>
                    <td>{{ $compilation->stage_academic_year}}</td>
                </tr>
                </tbody>
            </table>

            {{-- @todo split questions by section and add section title --}}
            <table class="table table-striped table-condensed">

                <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Question') }}</th>
                    <th class="col-sm-4 col-md-4 col-lg-4">{{ __('Answer') }}</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($compilation->items as $index => $item)
                    <tr>
                        <td>{{ ($index + 1) /* . ' [q' . $item->question->id . '].' */ }}</td>
                        <td>{{ $item->question->text }}</td>
                        <td>
                            @if (is_array($item->the_answer) === true)
                                <ul>
                                    @foreach ($item->the_answer as $answer)
                                        <li>{{ $answer->text }}</li>
                                    @endforeach
                                </ul>
                            @elseif ($item->the_answer !== null)
                                {{ $item->the_answer }}
                            @else

                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

        </div>

    </div>

@endsection
