@inject('compilationService', 'App\Services\CompilationService')

@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">

            <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
            {{ __('Compilation statistics') }}

            @if(empty(request()->all()) === false && empty($statistics) === false)
                ({{ array_sum($statistics['stage_location_id']) . ' ' . __('of') . ' ' . \App\Models\Compilation::count() }})
            @else
                ({{ \App\Models\Compilation::count() }})
            @endif

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

            {{-- @todo refactor by rendering the following code by JavaScript --}}
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
                <hr/>
            @endif
            
            @php
            $section = null;
            @endphp

            {{-- A container element for each question is created, together with its answers inside --}}
            @foreach ($statistics as $questionId => $answers)
                @if($compilationService->getQuestionSection($questionId) !== $section)
                    @php
                    $section = $compilationService->getQuestionSection($questionId);
                    @endphp
                    <h3>{{ $section->title }}</h3>
                @endif
                {{--  @todo refactor label array creation to another location --}}
                @php
                $labels = ['Compilations' => __('Compilations')];
                foreach (array_keys($answers) as $answerId) {
                    $labels[$answerId] = $compilationService->getAnswerText($answerId, $questionId);
                }
                @endphp
                <div id="chart_{{ $questionId }}"
                     style="width: 100%; height: {{ (count($answers) > 5 ? (30 * count($answers)) : 150) }}px;">
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
    
        var modalBody = $('#filterModal div.modal-body');
        
        $('div[id^=chart_]').each(function () {

            // Question/answers data is extracted from chart container tag.
            var data = JSON.parse($(this).find('.data').html());
            var question = data['question'];
            var answers = data['answers'];
            var labels = data['labels'];

            // Question/answers items are added to filter modal.
            modalBody.append(
                '<div class="clearfix row">' +
                '    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">' + question['text'] + '</div>' +
                '    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">' +
                '        <select name="' + question['id'] + '" style="width: 100%">' +
                '            <option></option>' +
                '        </select>' +
                '    </div>' +
                '</div>'
            );
            for (answerId in answers) {
                if (answers.hasOwnProperty(answerId) === false) {
                    continue;
                }
                modalBody.find('select:last').append('<option value="' + answerId + '">');
                modalBody.find('option:last').append(labels[answerId] + ' (' + answers[answerId] + ')');
                if (getUrlParameter(question['id']) === answerId) {
                    modalBody.find('option:last').prop('selected', 'selected');
                }
            }

            // Add question text before chart.
            $(this).before('<p>' + question['text'] + '</p>');

            // Chart creation
            // This function must be called here, after the previous code,
            // because it erases chart data from chart container tag.
            window.renderChart(this, data);

        });
    });
</script>
@endpush
