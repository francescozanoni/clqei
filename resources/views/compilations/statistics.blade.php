@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
        
            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
            
            {{ __('Compilations')  }}
            
        </div>

        <div class="panel-body">
        
            <div>{!! $chart->container() !!}</div>

            <!--
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js" charset="utf-8"></script>
            -->
            {!! $chart->script() !!}

        </div>

    </div>

@endsection
