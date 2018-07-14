@inject('compilationService', 'App\Services\CompilationService')

@extends('layouts.app')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">

            <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
            {{ __('Compilation statistics') }}

            @if(empty(request()->all()) === false && empty($statistics) === false)
                ({{ array_sum($statistics['stage_location_id']) . ' ' . __('of') . ' ' . \App\Models\Compilation::count() }})
            @else
                ({{ \App\Models\Compilation::count() }})
            @endif
            
        </div>

        <div class="panel-body">

            @if (empty($statistics) === true)
                {{ __('No compilations found') }}
            @endif
            
           {{-- Nav tabs --}}
            <ul class="nav nav-tabs" role="tablist" id="myTabs">
                @foreach ($sections as $index => $section)
                    <li role="presentation"
                           @if($index === 0)
                           class="active"
                           @endif
                    >
                        <a href="#section_{{ $section->id }}" aria-controls="section_{{ $section->id }}"
                           role="tab"
                           data-toggle="tab">
                            <span class="hidden-sm">{{ __('Section') }}</span>
                            <span class="visible-sm-inline">{{ __('Sect.') }}</span>
                            {{ $index }}</a>
                    </li>
                @endforeach
            </ul> 

{{-- Tab panes --}}
            <div class="tab-content">
            
             @php
             $section = null;
             @endphp
            
            {{-- A container element for each question is created, together with its answers inside --}}
            @foreach ($statistics as $questionId => $answers)
            
                    @if ($section === null)
                    @php
                     $section = $sections->first();
                     @endphp
                    
                    
                         <div role="tabpanel" class="tab-pane active" id="section_{{ $section->id }}">
                             <h3>
                             {{ __('Section') . ' ' . $section->id . ' - ' . $section->title }}
                             </h3>
                    @elseif($section->id !== ($compilationService->getQuestionSection($questionId) ?? $sections->first())->id)
                    @if ($section->footer !== null)
                            <em>{{ $section->footer }}</em>
                        @endif
                    @php
                    $section = $compilationService->getQuestionSection($questionId) ?? $sections->first();
                    @endphp
                    </div>
                    
                         <div role="tabpanel" class="tab-pane" id="section_{{ $section->id }}">
                             <h3>
                             {{ __('Section') . ' ' . $section->id . ' - ' . $section->title }}
                             </h3>
                    
                    @endif
            
                
                {{--  @todo refactor label array creation to another location --}}
                @php
                $labels = ['Compilations' => __('Compilations')];
                foreach (array_keys($answers) as $answerId) {
                    $labels[$answerId] = $compilationService->getAnswerText($answerId, $questionId);
                }
                @endphp
                <div id="count_{{ $questionId }}">
                    
                    <table class="table table-striped table-condensed">
                         <thead>
                         <tr class="row">
                         <th colspan="2">
                             {{ $compilationService->getQuestionText($questionId) }}
                         </th>
                         </tr>
                         </thead>
                         <tbody>
                          @foreach ($answers as $answerId => $count)
                            <tr class="row">
                                <td class="col-xs-8 col-sm-8 col-md-8 col-lg-8">{{ $labels[$answerId] }}</td>
                                <td class="col-xs-4 col-sm-4 col-md-4 col-lg-4">{{ $count }}</td>
                            </tr>
                          @endforeach
                         </tbody>
                     </table>
                   
                </div>
                
                @if (array_search($questionId, array_keys($statistics)) === count($statistics) - 1)
                    </div>
                @endif
                    
            @endforeach
            
            </div>

        </div>

    </div>

@endsection
