@inject('academicYearService', 'App\Services\AcademicYearService')
@inject('compilationService', 'App\Services\CompilationService')

@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">

            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            {{ __('New questionnaire compilation') }}

            {{-- On development environment, automatic form fill button is provided --}}
            @can('create', App\Models\Compilation::class)
            @if(app('env') === 'local')
                <button class="btn btn-primary btn-xs pull-right" onclick="fillForm()"
                        title="{{ __('Compilation form is filled with random values, except date fields') }}">
                    {{ __('Automatic compilation') }}
                </button>
            @endif
            @endcan

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

            {{-- Nav tabs --}}
            <ul class="nav nav-tabs" role="tablist" id="myTabs">
                {{-- Pseudo-section 0 --}}
                <li role="presentation" class="active">
                        <a href="#section_0" aria-controls="section_0" role="tab" data-toggle="tab">
                            <span class="hidden-sm">{{ __('Section') }}</span>
                            <span class="visible-sm-inline">{{ __('Sect.') }}</span>
                            0
                        </a>
                    </li>
                @foreach ($sections as $index => $section)
                    <li role="presentation">
                        <a href="#section_{{ $section->id }}" aria-controls="section_{{ $section->id }}"
                           role="tab"
                           data-toggle="tab">
                            <span class="hidden-sm">{{ __('Section') }}</span>
                            <span class="visible-sm-inline">{{ __('Sect.') }}</span>
                            {{ ($index + 1) }}</a>
                    </li>
                @endforeach
            </ul>

            @php
            // This variable allows not to rely on question IDs, that may be non-continuous.
            $questionCounter = 1;
            @endphp

            {{-- Tab panes --}}
            <div class="tab-content">
            
                {{-- Pseudo-section 0 --}}
                <div role="tabpanel" class="tab-pane active" id="section_0">

                        <h3>{{ __('Stage') }}</h3>
                        <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    {!! BootForm::select(
                            'stage_location_id',
                            $compilationService->getQuestionText('stage_location_id'),
                            App\Models\Location::all()->sortBy('name')->pluck('name', 'id'),
                            null,
                            ['placeholder' => __('Select') . '...']
                            ) !!}
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    {!! BootForm::select(
                            'stage_ward_id',
                            $compilationService->getQuestionText('stage_ward_id'),
                            App\Models\Ward::all()->sortBy('name')->pluck('name', 'id'),
                            null,
                            ['placeholder' => __('Select') . '...']
                            ) !!}
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    {!! BootForm::date(
                            'stage_start_date',
                            $compilationService->getQuestionText('stage_start_date'),
                            null,
                            ['placeholder' => __('YYYY-MM-DD')]
                            ) !!}
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    {!! BootForm::date(
                            'stage_end_date',
                            $compilationService->getQuestionText('stage_end_date'),
                            null,
                            ['placeholder' => __('YYYY-MM-DD')]
                            ) !!}
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                {!! BootForm::select(
                    'stage_academic_year',
                    $compilationService->getQuestionText('stage_academic_year'),
                    [
                    $academicYearService->getPrevious() => $academicYearService->getPrevious(),
                    $academicYearService->getCurrent() => $academicYearService->getCurrent()
                    ],
                    $academicYearService->getCurrent()
                    ) !!}
                    </div>
                        </div>
                        
                        <div class="pull-right">         
                                <a href="#section_1" aria-controls="section_1" role="tab" data-toggle="tab"
                                   onclick="$('#myTabs li:eq(1) a').tab('show')">
                                    {{ __('Go to section') . ' 1' }}
                                    <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
                                </a>     
                        </div>
                </div>
                        
                @foreach ($sections as $index => $section)
                    <div role="tabpanel" class="tab-pane" id="section_{{ $section->id }}">

                        <h3>{{ $section->title }}</h3>

                        @foreach ($section->questions as $question)

                            @if ($question->type === 'text')

                                {{-- Questions without predefined answers are free text --}}
                                {!! BootForm::textarea(
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
                                    $question->answers->sortBy('position')->pluck('text', 'id'),
                                    null,
                                    ['class' => 'radio-inline']
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

                        @if ($section->footer !== null)
                            <em>{{ $section->footer }}</em>
                        @endif

                        <div class="pull-right">
                            @if (isset($sections[$index + 1]) === true)
                                {{-- If this is not the last tab, a link to the next tab is displayed --}}
                                <a href="#section_{{ $sections[$index + 1]->id }}"
                                   aria-controls="section_{{ $sections[$index + 1]->id }}"
                                   role="tab"
                                   data-toggle="tab"
                                   onclick="$('#myTabs li:eq({{ ($index + 2) }}) a').tab('show')">
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

@endsection

{{-- On development environment, automatic form fill button is provided --}}
@can('create', App\Models\Compilation::class)
@if(app('env') === 'local')
    @push('scripts')
    <script src="{{ asset('js/compilation_form_filler.js') }}"></script>
    @endpush
@endif
@endcan
