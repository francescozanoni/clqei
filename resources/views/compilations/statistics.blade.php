@inject('compilationService', 'App\Services\CompilationService')

@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
            {{ __('Compilation statistics')  }}

            {{-- "Cancel filters" button is displayed only if any filters are active --}}
            @if (empty(request()->all()) === false)
                <button type="button" class="btn btn-primary btn-xs pull-right" style="margin-left:4px"
                        onclick="window.location.href='{{ route('compilations.statistics') }}'">
                    {{ __('Cancel filters') }}
                </button>
                @endif
                        <!-- Filter modal trigger button -->
                <button type="button" class="btn btn-primary btn-xs pull-right" data-toggle="modal"
                        data-target="#filterModal">
                    {{ __('Apply filters') }}
                </button>
        </div>

        <div class="panel-body">

            @if (empty($statistics) === true)
                {{ __('No compilations found') }}
            @endif

            @if (empty(request()->all()) === false)
                <h3>{{ __('Active filters') }}</h3>
                <ul class="list-group">
                    @foreach (request()->all() as $questionId => $answerId)
                        @if (empty($answerId) === false)
                            <li class="list-group-item">
                                {{ $compilationService->getQuestionText($questionId) }}:
                                <b>{{ $compilationService->getAnswerText($answerId, $questionId) }}</b>
                            </li>
                        @endif
                    @endforeach
                </ul>
            @endif

            {{-- A container element for each question is created, together with its answers inside --}}
            @foreach ($statistics as $questionId => $answers)
                {{--  @todo refactor label array creation to another location --}}
                @php
                $labels = ['Compilations' => __('Compilations')];
                foreach (array_keys($answers) as $answerId) {
                $labels[$answerId] = $compilationService->getAnswerText($answerId, $questionId);
                }
                @endphp
                <p>{{ $compilationService->getQuestionText($questionId) }}</p>
                <div id="chart_{{ $questionId }}"
                     style="width: 100%; height: {{ (count($answers) > 5 ? (30 * count($answers)) : 150) }}px;">
                    {{-- JSON-ized question ID and question --}}
                    <span class="hidden question">{!! json_encode([$questionId => $compilationService->getQuestionText($questionId)], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</span>
                    {{-- JSON-ized question statistics --}}
                    <span class="hidden answers">{!! json_encode($answers, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</span>
                    {{-- JSON-ized answer texts, localized --}}
                    <span class="hidden labels">{!! json_encode($labels, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</span>
                </div>
            @endforeach

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
        $('div[id^=chart_]').each(function () {

            var questionId = Object.keys(JSON.parse($(this).find('.question').html()))[0];
            var question = JSON.parse($(this).find('.question').html())[questionId];
            var data = JSON.parse($(this).find('.answers').html());
            var labels = JSON.parse($(this).find('.labels').html());

            // Chart type is chosen according to the number of different answers.
            if (Object.keys(data).length > 5) {
                HighchartsBarFactory.create(this, questionId, data, labels);
            } else {
                HighchartsStackedBarFactory.create(this, questionId, data, labels);
            }

            // Pie chart is currently unused
            // HighchartsPieFactory.create(this, questionId, data, labels);

            $('#filterModal div.modal-body').append('<div class="clearfix">' + question + '</div>');
            $('#filterModal div.modal-body > div:last-child').append('<select name="' + questionId + '" class="pull-right">');
            $('#filterModal div.modal-body > div:last-child select').append('<option>');
            for (answerId in data) {
                $('#filterModal div.modal-body > div:last-child select').append('<option value="' + answerId + '">');
                $('#filterModal div.modal-body > div:last-child select option:last-child').append(labels[answerId] + ' (' + data[answerId] + ')');
                if (getUrlParameter(questionId) === answerId) {
                    $('#filterModal div.modal-body > div:last-child select option:last-child').prop('selected', 'selected');
                }
            }

        });
    });
</script>
@endpush
