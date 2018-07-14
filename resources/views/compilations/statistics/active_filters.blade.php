@inject('compilationService', 'App\Services\CompilationService')


            {{-- @todo refactor by rendering the following code by JavaScript --}}
           
                <h3>{{ __('Active filters') }}</h3>
                <ul class="list-group">
                    @foreach ($activeFilters as $questionId => $answerId)
                        @if (empty($answerId) === false)
                            <li class="list-group-item">
                                {{ $compilationService->getQuestionText($questionId) }}:
                                <b>{{ $compilationService->getAnswerText($answerId, $questionId) }}</b>
                            </li>
                        @endif
                    @endforeach
                </ul>
                <hr/>