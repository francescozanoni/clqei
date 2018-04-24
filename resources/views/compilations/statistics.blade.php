@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
        
            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
            
            {{ __('Compilations')  }}
            
        </div>

        <div class="panel-body">
        
            <div>{!! $chart->container() !!}</div>

            {!! $chart->script() !!}

        </div>

    </div>

@endsection
