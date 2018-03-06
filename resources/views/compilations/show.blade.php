@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                @if (session(\App\Observers\ModelObserver::FLASH_MESSAGE_KEY))
                    <div class="alert alert-success">
                        {{ session(\App\Observers\ModelObserver::FLASH_MESSAGE_KEY) }}
                    </div>
                @endif

                <div class="panel panel-default">

                    <div class="panel-heading">
                        <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                        {{-- @todo refactor date localization to a service --}}
                        {{ __('Compilation of') . ' ' . (new Carbon\Carbon($compilation->created_at))->format('d/m/Y') }}

                        <div class="pull-right hidden-print" onclick="window.print()" style="cursor:pointer">
                            &nbsp;
                            &nbsp;
                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                            {{ __('Print') }}
                        </div>

                        <div class="pull-right{{ Auth::user()->can('viewAll', App\Models\Compilation::class) ? '' : ' visible-print-block' }}">
                            {{ $compilation->student->user->first_name }}
                            {{ $compilation->student->user->last_name }}
                            -
                            {{ $compilation->student->identification_number }}
                        </div>

                    </div>

                    <div class="panel-body">

                        <table class="table table-striped table-condensed">
                            <thead>
                            <tr>
                                <th>{{ __('Stage') }}</th>
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
                                    {{ (new Carbon\Carbon($compilation->stage_start_date))->format('d/m/Y') }}
                                    -
                                    {{ (new Carbon\Carbon($compilation->stage_end_date))->format('d/m/Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Weeks') }}</td>
                                <td>{{ $compilation->getStageWeeks() }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Academic year') }}</td>
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
            </div>
        </div>
    </div>
@endsection
