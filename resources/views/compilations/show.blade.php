@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">

                        {{ __('Compilation') . ': ' . $compilation->created_at }}

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
                            <dd>{{ $compilation->stage_start_date }}</dd>
                            <dt>{{ __('Stage end date') }}</dt>
                            <dd>{{ $compilation->stage_end_date }}</dd>
                        </dl>

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
                                        @if (is_array($item->answer) === true)
                                            <ul>
                                                @foreach ($item->answer as $answer)
                                                    <li>{{ $answer }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            {{ $item->answer }}
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
