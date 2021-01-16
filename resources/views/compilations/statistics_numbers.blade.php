@inject('compilationService', 'App\Services\CompilationService')

@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        {{-- @todo merge this tag with statistics_charts.blade.php --}}
        <div class="panel-heading">

            <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
            {{ __('Compilation statistics') }}

            @if(empty($filters) === false && empty($statistics) === false && empty($statistics['counts']) === false)
                ({{
                array_sum($statistics['counts']['stage_location_id']) .
                ' ' .
                __('of') .
                ' ' .
                \App\Models\Compilation::count()
                }})
            @else
                ({{ \App\Models\Compilation::count() }})
            @endif
            - ({!! link_to_route('compilations.statistics_charts', __('charts'), $filters) !!})

            @if (empty($statistics) === false && empty($statistics['counts']) === false)
                {{-- "Cancel filters" button is displayed only if any filters are active --}}
                @if (empty($filters) === false)
                    <button type="button" class="btn btn-primary btn-xs pull-right" style="margin-left:4px"
                            onclick="window.location.href='{{ route('compilations.statistics_numbers') }}'">
                        {{ __('Cancel filters') }}
                    </button>
                @endif

                <!-- Filter modal trigger button -->
                <button type="button" class="btn btn-primary btn-xs pull-right" data-toggle="modal"
                        data-target="#filterModal">
                    {{ __('Apply filters') }}
                </button>
            @endif

        </div>

        <div class="panel-body">

            @if (empty($statistics) === true || empty($statistics['counts']) === true)
                {{ __('No compilations found') }}
            @endif

            @includeWhen(
                empty($filters) === false,
                'compilations.statistics.active_filters',
                ['activeFilters' => $filters]
            )

            {{-- Nav tabs --}}
            {{-- @todo disable tab when no data is available --}}
            <ul class="nav nav-tabs" role="tablist" id="myTabs">
                @foreach ($sections as $index => $section)
                    <li role="presentation"
                        @if($index === 0)
                        class="active"
                            @endif
                    >
                        <a href="#section_{{ $section->id }}" aria-controls="section_{{ $section->id }}"
                           role="tab"
                           data-toggle="tab">
                            <span class="hidden-sm">{{ __('Section') }}</span>
                            <span class="visible-sm-inline">{{ __('Sect.') }}</span>
                            {{ $index }}</a>
                    </li>
                @endforeach
            </ul>

            {{-- Tab panes --}}
            <div class="tab-content">

                @php
                    $section = null;
                @endphp

                {{-- A container element for each question is created, together with its answers inside --}}
                @foreach ($statistics['counts'] as $questionId => $answers)

                    @if ($section === null)

                        @php
                            $section = $sections->first();
                        @endphp

                        <div role="tabpanel" class="tab-pane active" id="section_{{ $section->id }}">
                            <h3>
                                {{ $section->title }}
                            </h3>

                    @elseif($section->id !== ($compilationService->getQuestionSection($questionId) ?? $sections->first())->id)

                            @if ($section->footer !== null)
                                <em>{{ $section->footer }}</em>
                            @endif
                            @php
                                $section = $compilationService->getQuestionSection($questionId) ?? $sections->first();
                            @endphp
                        </div>

                        <div role="tabpanel" class="tab-pane" id="section_{{ $section->id }}">
                            <h3>
                                {{ $section->title }}
                            </h3>

                    @endif

                    {{-- @todo refactor label array creation to another location --}}
                    @php
                        $labels = ['Compilations' => __('Compilations')];
                        foreach (array_keys($answers) as $answerId) {
                        $labels[$answerId] = $compilationService->getAnswerText($answerId, $questionId);
                        }
                    @endphp
                    <div id="count_{{ $questionId }}">

                        <table class="table table-striped table-condensed">
                            <thead>
                            <tr class="row">
                                <th colspan="3">
                                    {{ $compilationService->getQuestionText($questionId) }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                @if ($compilationService->isFreeTextQuestion($questionId) === true)
                                    {{-- Free text answer counts are meaningless --}}
                                    @foreach (array_keys($answers) as $answer)
                                        <tr class="row">
                                            {{-- https://stackoverflow.com/questions/28569955/how-do-i-use-nl2br-in-laravel-5-blade --}}
                                            <td colspan="3">{!! nl2br(e($answer)) !!}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach ($answers as $answerId => $count)
                                        <tr class="row">
                                            <td class="col-xs-10 col-sm-10 col-md-10 col-lg-10">{{ $labels[$answerId] }}</td>
                                            <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">{{ $count }}</td>
                                            <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">{{ sprintf("%.2f%%", $statistics['percentages'][$questionId][$answerId] * 100) }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                        {{-- Question/answers data used to populate filters --}}
                        {{-- Free-text questions/answers cannot be used with filters --}}
                        @if ($compilationService->isFreeTextQuestion($questionId) === false)
                            <span class="hidden data">
                                @jsonize([
                                    'question' => [
                                        'id' => $questionId,
                                        'text' => $compilationService->getQuestionText($questionId),
                                    ],
                                    'answers' => $answers,
                                    'labels' => $labels,
                                ])
                            </span>
                        @endif

                    </div>

                    @if (array_search($questionId, array_keys($statistics['counts'])) === count($statistics['counts']) - 1)
                        </div>
                    @endif

                @endforeach

            </div>

        </div>

    </div>

    <!-- Filter modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="filterModalLabel">{{ __('Compilation filters') }}</h4>
                </div>
                <form>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Apply') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/statistics.js') }}"></script>
    <script>
        $(function () {

            var modalBody = $('#filterModal div.modal-body');

            $('div[id^=count_]').each(function () {

                // Question/answers data is extracted from count container tag.
                // Free-text questions/answers do not have data, in order to exclude them from filters.
                var rawData = $(this).find('.data').html();
                if (rawData === undefined) {
                    return;
                }
                var data = JSON.parse(rawData);

                window.addFilterToModal(modalBody, data, getUrlParameters());

            });
        });
    </script>
@endpush