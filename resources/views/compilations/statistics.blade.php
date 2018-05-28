@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">

            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>

            {{ __('Compilations')  }}

        </div>

        <div class="panel-body">

            <!--
            <div id="container_1" style="width: 100%; height: 200px;"></div>
            <div id="container_1_data" class="hidden"></div>
            <div id="container_2" style="width: 100%; height: 200px;"></div>
            <div id="container_3" style="width: 100%; height: 200px;"></div>
            <div id="container_4" style="width: 100%; height: 200px;"></div>
            <div id="container_5" style="width: 100%; height: 200px;"></div>
            <div id="container_6" style="width: 100%; height: 200px;"></div>
            -->

            <pre>{{ print_r($statistics, true) }}</pre>

        </div>

    </div>

@endsection

@push('scripts')
<script>
    // https://www.highcharts.com/maps-demo/bar-stacked
    // https://www.highcharts.com/maps-demo/column-stacked-percent
    /*
    $(function () {
        Highcharts.chart(
                'container_1',
                {
                    chart: {type: 'bar'},
                    title: {text: 'Stage locations'},
                    legend: {reversed: true},
                    plotOptions: {series: {stacking: 'percent'}},
                    series: JSON.parse($('#container_1_data').html())
                }
        );
    });
    */
</script>
@endpush
