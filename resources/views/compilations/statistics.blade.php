@inject('compilationService', 'App\Services\CompilationService')

@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">

            <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>

            {{ __('Compilation statistics')  }}

        </div>

        <div class="panel-body">

            @if (empty($statistics) === true)
                {{ __('No compilations found') }}
            @endif

            {{-- A container element for each question is created, together with its answers inside --}}
            @foreach ($statistics as $questionId => $answers)
                @php
                $labels = ['Compilations' => __('Compilations')];
                foreach (array_keys($answers) as $answerId) {
                    $labels[$answerId] = $compilationService->getAnswerText($answerId, $questionId);
                }
                @endphp
                <p>{{ $compilationService->getQuestionText($questionId) }}</p>
                <div id="chart_{{ $questionId }}" style="width: 100%; height: {{ (count($answers) > 5 ? (40 * count($answers)) : 150) }}px;">
                    {{-- JSON-ized question statistics --}}
                    <span class="hidden answers">{!! json_encode($answers, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</span>
                    {{-- JSON-ized answer texts, localized --}}
                    <span class="hidden labels">{!! json_encode($labels, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</span>
                </div>
            @endforeach

        </div>

    </div>

@endsection

@push('scripts')
<script src="{{ asset('js/statistics.js') }}"></script>
<script>
    $(function () {
        $('div[id^=chart_]').each(function () {
            var data = JSON.parse($(this).find('.answers').html());
            var labels = JSON.parse($(this).find('.labels').html());
            /*
             HighchartsPieFactory.create(
             this,
             data,
             labels
             );
             */
            if (Object.keys(data).length > 5) {
                HighchartsBarFactory.create(
                        this,
                        data,
                        labels
                );
            } else {
                HighchartsStackedBarFactory.create(
                        this,
                        data,
                        labels
                );
            }


        });
    });
</script>
@endpush
