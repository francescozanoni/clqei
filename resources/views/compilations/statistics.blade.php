@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">

            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>

            {{ __('Compilations')  }}

        </div>

        <div class="panel-body">

            @foreach ($statistics as $question => $answers)
                <div id="container_{{ $question }}" style="width: 80%; height: 200px;"></div>
                <span id="container_{{ $question }}_data" class="hidden">
                    {!! json_encode($answers, JSON_UNESCAPED_SLASHES) !!}
                </span>
            @endforeach

        </div>

    </div>

@endsection

@push('scripts')
<script src="{{ asset('js/statistics.js') }}"></script>
<script>

    // https://www.highcharts.com/maps-demo/bar-stacked
    // https://www.highcharts.com/maps-demo/column-stacked-percent
    $(function () {
        @foreach ($statistics as $question => $answers)
            Highcharts.chart(
                'container_{{ $question }}',
                {
                    chart: {type: 'bar'},
                    title: {
                        text: '{{ $question }} (%)',
                        align: 'left'
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        width: 100
                    },
                    yAxis: {
                        title: {
                            text: ''
                        }
                    },
                    plotOptions: {series: {stacking: 'percent'}},
                    series: JSON.parse($('#container_{{ $question }}_data').html())
                }
            );
        @endforeach
    });
</script>
@endpush
