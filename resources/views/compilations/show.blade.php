@inject('compilationService', 'App\Services\CompilationService')

@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
            {{-- @todo refactor date localization to a service --}}
            {{ __('Compilation of') . ' ' . (new Carbon\Carbon($compilation->created_at))->format('d/m/Y') }}

            <div class="pull-right hidden-print"
                 style="cursor:pointer"
                 {{-- @todo switch to server-generated URL --}}
                 onclick="window.open(window.location.href + '?receipt', '_blank')"
                 title="{{ __('Print compilation receipt') }}">
                <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                <span class="hidden-xs">{{ __('Receipt') }}</span>
            </div>

        </div>

        <div class="panel-body">

        @if (Auth::user()->can('viewAll', App\Models\Compilation::class))
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th colspan="2">
                        <h3>
                            {{ __('Student') }}
                            <small>
                            (<a href="{{ route('users.show', ['user' => $compilation->student->user]) }}">
                                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                {{ __('Profile') }}
                            </a>)
                            </small>
                        </h3>
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
        @endif

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

                    <td class="col-sm-8 col-md-8 col-lg-8">{{ $compilationService->getQuestionText('stage_location_id') }}</td>
                    <td class="col-sm-4 col-md-4 col-lg-4">{{ $compilation->stageLocation->name }}</td>
                </tr>
                <tr>

                    <td class="col-sm-8 col-md-8 col-lg-8">{{ $compilationService->getQuestionText('stage_ward_id') }}</td>
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

                    <td class="col-sm-8 col-md-8 col-lg-8">{{ $compilationService->getQuestionText('stage_weeks') }}</td>
                    <td class="col-sm-4 col-md-4 col-lg-4">{{ $compilation->stage_weeks }}</td>
                </tr>
                <tr>
                    <td class="col-sm-8 col-md-8 col-lg-8">
                        <span class="hidden-xs"> {{ $compilationService->getQuestionText('stage_academic_year') }} </span>
                        <span class="visible-xs-inline hidden-print"> {{ __('Acad. year') }} </span>
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
                             <h3 class="hidden-xs hidden-print">
                             {{ __('Section') . ' ' . $item->question->section->id . ' - ' . $item->question->section->title }}
                             </h3>
                             <h3 class="visible-xs-inline hidden-print">
                             {{ __('Sect.') . ' ' . $item->question->section->id . ' - ' . $item->question->section->title }}
                             </h3>
                             <h3 class="visible-print-inline">
                             {{ __('Section') . ' ' . $item->question->section->id . ' - ' . $item->question->section->title }}
                             </h3>
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

                        @if ($item->question->section->footer !== null)
                            <em>{{ $item->question->section->footer }}</em>
                        @endif

                    @endif

                @endforeach

        </div>

    </div>

@endsection
