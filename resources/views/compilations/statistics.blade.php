@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">

            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>

            {{ __('Compilations')  }}

        </div>

        <div class="panel-body">

            {{-- A container element for each question is created, together with its answers inside --}}
            @foreach ($statistics as $question => $answers)
                <div id="chart_{{ $question }}" data-question="{{ $question }}" style="width: 100%; height: 400px;">
                    {{-- JSON-ized question statistics --}}
                    <span class="hidden">{!! json_encode($answers, JSON_UNESCAPED_SLASHES) !!}</span>
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
            createHighchartsPie(
                    this,
                    $(this).data('question'),
                    formatHighchartsPie(JSON.parse($(this).find('span.hidden').html())),
                    {'Compilations': '{{ __('Compilations') }}'}
            );
        });
    });
</script>
@endpush
