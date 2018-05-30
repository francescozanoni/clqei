@inject('compilationService', 'App\Services\CompilationService')

@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">

            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>

            {{ __('Compilations')  }}

        </div>

        <div class="panel-body">

            {{-- A container element for each question is created, together with its answers inside --}}
            @foreach ($statistics as $questionId => $answers)
                @php
                $labels = ['Compilations' => __('Compilations')];
                foreach (array_keys($answers) as $answerId) {
                $labels[(string)$answerId] = $compilationService->getAnswerText((string)$answerId, $questionId);
                }
                @endphp
                <div id="chart_{{ $questionId }}"
                     data-question="{{ $compilationService->getQuestionText($questionId) }}"
                     style="width: 100%; height: 400px;">
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
            let data = JSON.parse($(this).find('.answers').html());
            let labels = JSON.parse($(this).find('.labels').html());
            createHighchartsPie(
                    this,
                    $(this).data('question'),
                    formatHighchartsPie(data, labels),
                    labels
            );
        });
    });
</script>
@endpush
