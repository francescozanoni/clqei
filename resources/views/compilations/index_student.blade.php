@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
            {{ __('My compilations') }}
        </div>

        <div class="panel-body">

            @if ($compilations->isEmpty() === true)

                {{ __('No compilations found') }}

            @else

                <table class="table">

                    <thead>
                    <tr>
                        <th>{{ __('Stage location') }}</th>
                        <th>{{ __('Stage ward') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($compilations as $compilation)
                        <tr>
                            <td>{{ $compilation->stageLocation->name }}</td>
                            <td>{{ $compilation->stageWard->name }}</td>
                            <td>
                            <span class="hidden-xs"> {{ (new Carbon\Carbon($compilation->created_at))->format('d/m/Y') }}
                            </span>
                        <span class="visible-xs-inline">
                        
                            {{ (new Carbon\Carbon($compilation->created_at))->format('d/m/y') }}
                            </span>
                            </td>
                            <td>
                                <a href="{{ route('compilations.show', ['compilation' => $compilation]) }}"
                                   title="{{ __('View') }}">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>

            @endif

        </div>

    </div>

@endsection
