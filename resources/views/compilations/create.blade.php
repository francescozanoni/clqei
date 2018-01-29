@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">{{ __('New compilation') }}</div>

                    <div class="panel-body">

                        {!! BootForm::open(['route' => 'compilations.store', 'method' => 'post']) !!}

                        {{-- http://www.easylaravelbook.com/blog/creating-and-validating-a-laravel-5-form-the-definitive-guide/ --}}
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                {{ __('There were some problems adding the compilation') }}
                                <br/>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {!! BootForm::hidden('student_id', Auth::user()->student->id) !!}

                        {!! BootForm::select(
                                'stage_location_id',
                                __('Stage location'),
                                App\Models\Location::all()->pluck('name', 'id'),
                                null,
                                ['placeholder' => __('Select') . '...']
                                ) !!}
                        {!! BootForm::select(
                                'stage_ward_id',
                                __('Stage ward'),
                                App\Models\Ward::all()->pluck('name', 'id'),
                                null,
                                ['placeholder' => __('Select') . '...']
                                ) !!}
                        {!! BootForm::date('stage_start_date', __('Stage start date')) !!}
                        {!! BootForm::date('stage_end_date', __('Stage end date')) !!}
                        {{-- @todo make academic year list dynamic --}}
                        {!! BootForm::select(
                                'stage_academic_year',
                                __('Stage academic year'),
                                [
                                '2016/2017' => '2016/2017',
                                '2017/2018' => '2017/2018',
                                '2018/2019' => '2018/2019',
                                ],
                                null,
                                ['placeholder' => __('Select') . '...']
                                ) !!}

                        {{-- Nav tabs --}}
                        <ul class="nav nav-tabs" role="tablist" id="myTabs">
                            @foreach ($sections as $index => $section)
                                <li role="presentation"
                                    @if ($section->id === 1)
                                    class="active"
                                        @endif
                                >
                                    <a href="#section_{{ $section->id }}" aria-controls="section_{{ $section->id }}"
                                       role="tab"
                                       data-toggle="tab">{{ __('Section') . ' ' . ($index + 1) }}</a>
                                </li>
                            @endforeach
                        </ul>

                        @php
                        // This variable is used in order not to rely on question IDs,
                        // that may be non-continuous.
                        $questionCounter = 1;
                        @endphp

                        {{-- Tab panes --}}
                        <div class="tab-content">
                            @foreach ($sections as $section)
                                <div role="tabpanel"
                                     @if ($section->id === 1)
                                     class="tab-pane active"
                                     @else
                                     class="tab-pane"
                                     @endif
                                     id="section_{{ $section->id }}">

                                     <h3>{{ $section->text }}</h3>

                                    @foreach ($section->questions as $question)

                                        @if ($question->type === 'text')

                                            {{-- Questions without predefined answers are free text --}}
                                            {!! BootForm::text(
                                            'q' . $question->id,
                                            $questionCounter++ . ' [q' . $question->id . ']. ' . $question->text
                                            ) !!}

                                        @endif

                                        @if ($question->type === 'multiple_choice')

                                            {!! BootForm::checkboxes(
                                            'q' . $question->id . '[]',
                                            $questionCounter++ . ' [q' . $question->id . ']. ' . $question->text,
                                            $question->answers->pluck('text', 'id')
                                            ) !!}

                                        @endif

                                        @if ($question->type === 'single_choice')

                                            @if (count($question->answers) < 5)

                                                {{-- Questions with less than 5 answers are rendered as radio buttons --}}
                                                {!! BootForm::radios(
                                                'q' . $question->id,
                                                $questionCounter++ . ' [q' . $question->id . ']. ' . $question->text,
                                                $question->answers->pluck('text', 'id')
                                                ) !!}

                                            @else

                                                {{-- Questions with 5 or more answers are rendered as select boxes --}}
                                                {!! BootForm::select(
                                                'q' . $question->id,
                                                $questionCounter++ . ' [q' . $question->id . ']. ' . $question->text,
                                                $question->answers->pluck('text', 'id'),
                                                null,
                                                ['placeholder' => __('Select') . '...']
                                                ) !!}

                                            @endif

                                        @endif

                                        @if ($question->type === 'date')

                                            {!! BootForm::date(
                                            'q' . $question->id,
                                            $questionCounter++ . ' [q' . $question->id . ']. ' . $question->text
                                            ) !!}

                                        @endif

                                    @endforeach

                                </div>
                            @endforeach
                        </div>

                        {!! BootForm::submit(__('Create')) !!}

                        {!! BootForm::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
