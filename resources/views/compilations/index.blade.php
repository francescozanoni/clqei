@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">{{ __('My compilations') }}</div>

                    <div class="panel-body">

                        @if ($compilations->isEmpty() === false)
                        
                            <ul>
                                @foreach ($compilations as $compilation)
                                    <li>
                                        <code>{{ $compilation }}</code>
                                        {!! link_to_route('compilations.show', __('View'), ['compilation' => $compilation]) !!}
                                    </li>
                                @endforeach
                            </ul>
                        
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
