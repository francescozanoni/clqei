@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-heading">Create</div>

                    <div class="panel-body">

                        {!! BootForm::open(['route' => 'compilations.store', 'method' => 'post']) !!}

                        {{-- http://www.easylaravelbook.com/blog/creating-and-validating-a-laravel-5-form-the-definitive-guide/ --}}
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                There were some problems adding the category.<br/>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        {!! BootForm::hidden('student_id', Auth::user()->student->id) !!}

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
                                            $questionCounter++ . '. ' . $question->text
                                            ) !!}

                                        @endif
                                        
                                        @if ($question->type === 'multiple_choice')

                                            {!! BootForm::checkboxes(
                                                'q' . $question->id . '[]',
                                                $questionCounter++ . '. ' . $question->text,
                                                $question->answers->pluck('text', 'id')
                                                ) !!}

                                        @endif
                                        
                                        @if ($question->type === 'single_choice')

                                            @if (count($question->answers) < 5)

                                                {{-- Questions with less than 5 answers are rendered as radio buttons --}}
                                                {!! BootForm::radios(
                                                'q' . $question->id,
                                                $questionCounter++ . '. ' . $question->text,
                                                $question->answers->pluck('text', 'id')
                                                ) !!}

                                            @else

                                                {{-- Questions with 5 or more answers are rendered as select boxes --}}
                                                {!! BootForm::select(
                                                'q' . $question->id,
                                                $questionCounter++ . '. ' . $question->text,
                                                $question->answers->pluck('text', 'id'),
                                                null,
                                                ['placeholder' => __('Select') . '...']
                                                ) !!}

                                            @endif

                                        @endif

                                    @endforeach

                                </div>
                            @endforeach
                        </div>

                        {!! BootForm::submit('Create!') !!}

                        {!! BootForm::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
