@inject('academicYearService', 'App\Services\AcademicYearService')

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                        {{ __('New questionnaire compilation') }}
                    </div>

                    <div class="panel-body">

                        @can('create', App\Models\Compilation::class)
                        
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
                        
                        @endcan

                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                {!! BootForm::select(
                                        'stage_location_id',
                                        __('Stage location'),
                                        App\Models\Location::all()->sortBy('name')->pluck('name', 'id'),
                                        null,
                                        ['placeholder' => __('Select') . '...']
                                        ) !!}
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                {!! BootForm::select(
                                        'stage_ward_id',
                                        __('Stage ward'),
                                        App\Models\Ward::all()->sortBy('name')->pluck('name', 'id'),
                                        null,
                                        ['placeholder' => __('Select') . '...']
                                        ) !!}
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                {!! BootForm::date('stage_start_date', __('Stage start date')) !!}
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                {!! BootForm::date('stage_end_date', __('Stage end date')) !!}
                            </div>
                        </div>

                        {!! BootForm::select(
                                'stage_academic_year',
                                __('Stage academic year'),
                                [
                                $academicYearService->getPrevious() => $academicYearService->getPrevious(),
                                $academicYearService->getCurrent() => $academicYearService->getCurrent(),
                                $academicYearService->getNext() => $academicYearService->getNext(),
                                ],
                                $academicYearService->getCurrent()
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
                            @foreach ($sections as $index => $section)
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
                                            $question->answers->sortBy('position')->pluck('text', 'id')
                                            ) !!}

                                        @endif

                                        @if ($question->type === 'single_choice')

                                            @if (count($question->answers) < 5)

                                                {{-- Questions with less than 5 answers are rendered as radio buttons --}}
                                                {!! BootForm::radios(
                                                'q' . $question->id,
                                                $questionCounter++ . ' [q' . $question->id . ']. ' . $question->text,
                                                $question->answers->sortBy('position')->pluck('text', 'id')
                                                ) !!}

                                            @else

                                                {{-- Questions with 5 or more answers are rendered as select boxes --}}
                                                {!! BootForm::select(
                                                'q' . $question->id,
                                                $questionCounter++ . ' [q' . $question->id . ']. ' . $question->text,
                                                $question->answers->sortBy('position')->pluck('text', 'id'),
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

                                    <div class="pull-right">
                                        @if (isset($sections[$index + 1]) === true)
                                            {{-- If this is not the last tab, a link to the next tab is displayed --}}
                                            <a href="#section_{{ $sections[$index + 1]->id }}"
                                               aria-controls="section_{{ $sections[$index + 1]->id }}"
                                               role="tab"
                                               data-toggle="tab"
                                               onclick="$('#myTabs li:eq({{ ($index + 1) }}) a').tab('show')">
                                                {{ __('Go to section') . ' ' . ($index + 2) }}
                                                <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
                                            </a>
                                        @else
                                            {{-- Otherwise, the submit button is displayed --}}
                                            @can('create', App\Models\Compilation::class)
                                                {!! BootForm::submit(__('Save')) !!}
                                            @endcan
                                            @cannot('create', App\Models\Compilation::class)
                                                {!! BootForm::submit(
                                                    __('Save'),
                                                    ['disabled' => 'disabled']
                                                ) !!}
                                            @endcannot
                                        @endif
                                    </div>

                                </div>
                            @endforeach
                        </div>

                        @can('create', App\Models\Compilation::class)
                            {!! BootForm::close() !!}
                        @endcan

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
