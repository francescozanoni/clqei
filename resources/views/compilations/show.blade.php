@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="panel panel-default">

                    <div class="panel-heading">

                        {{-- @todo refactor date localization to a service --}}
                        {{ __('Compilation') . ': ' . (new Carbon\Carbon($compilation->created_at))->format('d/m/Y') }}

                        @can('viewAll', App\Models\Compilation::class)
                        -
                        {{ $compilation->student->identification_number }}
                        -
                        {{ $compilation->student->user->last_name }}
                        {{ $compilation->student->user->first_name }}
                        @endcan

                    </div>

                    <div class="panel-body">

                        <dl class="dl-horizontal">
                            <dt>{{ __('Stage location') }}</dt>
                            <dd>{{ $compilation->stageLocation->name }}</dd>
                            <dt>{{ __('Stage ward') }}</dt>
                            <dd>{{ $compilation->stageWard->name }}</dd>
                            <dt>{{ __('Stage start date') }}</dt>
                            {{-- @todo refactor date localization to a service --}}
                            <dd>{{ (new Carbon\Carbon($compilation->stage_start_date))->format('d/m/Y') }}</dd>
                            <dt>{{ __('Stage end date') }}</dt>
                            {{-- @todo refactor date localization to a service --}}
                            <dd>{{ (new Carbon\Carbon($compilation->stage_end_date))->format('d/m/Y') }}</dd>
                            <dt>{{ __('Stage academic year') }}</dt>
                            <dd>{{ $compilation->stage_academic_year}}</dd>
                        </dl>

                        {{-- @todo split questions by section and add section title --}}
                        <table class="table">

                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Question') }}</th>
                                <th>{{ __('Answer') }}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($compilation->items as $item)
                                <tr>
                                    <td>{{ $item->question->id }}</td>
                                    <td>{{ $item->question->text }}</td>
                                    <td>
                                        @if (is_array($item->the_answer) === true)
                                            <ul>
                                                @foreach ($item->the_answer as $answer)
                                                    <li>{{ $answer->text }}</li>
                                                @endforeach
                                            </ul>
                                        @elseif ($item->the_answer !== null)
                                            {{ $item->the_answer }}
                                        @else

                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
