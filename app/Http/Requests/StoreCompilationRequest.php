<?php
declare(strict_types = 1);

namespace App\Http\Requests;

use App;
use App\Models\Compilation;
use App\Models\Question;
use App\Services\AcademicYearService;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreCompilationRequest extends FormRequest
{

    /**
     * @var array Queue reporting question mandatority depending on the answer of a previous question
     */
    private $mandatorityQueue = [];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();
        return $user->can('create', Compilation::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param AcademicYearService $academicYearService
     * @return array
     */
    public function rules(AcademicYearService $academicYearService)
    {

        // Fixed question rules.
        $rules = [
            'student_id' => 'required|exists:students,id|in:' . Auth::user()->student->id,
            'stage_location_id' => 'required|exists:locations,id',
            'stage_ward_id' => 'required|exists:wards,id',
            'stage_start_date' => [
                'bail',
                'required',
                'date_format:Y-m-d',
                'before:today',
                'not_overlapping_time_range:stage_end_date,compilations,stage_start_date,stage_end_date,student_id',
            ],
            'stage_end_date' => [
                'bail',
                'required',
                'date_format:Y-m-d',
                'after:stage_start_date',
                'before:' . $this->getMaxStageEndDate($this->stage_start_date ?? ''),
                'not_overlapping_time_range:stage_start_date,compilations,stage_start_date,stage_end_date,student_id',
            ],
            'stage_academic_year' => 'required|in:' . implode(',', [
                    $academicYearService->getPrevious(),
                    $academicYearService->getCurrent()
                ])
        ];

        // Variable question rules.
        $questions = Question::all();

        foreach ($questions as $question) {

            $rules['q' . $question->id] = [];

            foreach ($this->getSingleQuestionRules($question) as $rule) {
                $rules['q' . $question->id][] = $rule;
            }

            $this->updateMandatorityQueue($question);

        }

        return $rules;
    }

    /**
     * Stage end date must be:
     *  - before one week from today,
     *  - before N weeks after stage start date.
     *
     * @param string $stageStartDate
     * @return string
     */
    private function getMaxStageEndDate(string $stageStartDate) : string
    {
        $oneWeekFromToday = Carbon::today()->addWeeks(1);
        
        // If stage start date is empty or invalid,
        // one week from today date is returned.
        if ($stageStartDate === '' ||
            \DateTime::createFromFormat('Y-m-d', $stageStartDate) === false) {
            return $oneWeekFromToday->format('Y-m-d');
        }

        $maxStageEndDate = Carbon::parse($stageStartDate)->addWeeks(config('clqei.stages.max_weeks'));
        if ($oneWeekFromToday < $maxStageEndDate) {
            $maxStageEndDate = $oneWeekFromToday;
        }

        return $maxStageEndDate->format('Y-m-d');
    }

    /**
     * Get validation rules of a single question (dynamic questions, with key "qN").
     *
     * @param Question $question
     * @return \Generator
     */
    private function getSingleQuestionRules(Question $question)
    {

        // https://laravel.com/docs/5.5/validation#a-note-on-optional-fields
        yield ($question->required == true ? 'required' : 'nullable');

        if (in_array($question->type, ['single_choice', 'multiple_choice']) === true) {
            yield Rule::exists('answers', 'id')
                ->where(function ($query) use ($question) {
                    $query->where('question_id', $question->id);
                });
        }

        if ($question->type === 'date') {
            yield 'date';
        }

        // Reading of the mandatority queue, in case the current question is
        // required according to the value of a previous question.
        $requirement = array_pop($this->mandatorityQueue);
        if ($requirement !== null) {
            yield 'required_if:q' . $requirement['question'] . ',' . $requirement['answer'];
        }
    }

    /**
     * If a specific answer of the input question makes next question(s) mandatory,
     * one or more items are added to mandatority queue.
     *
     * @param Question $question
     */
    private function updateMandatorityQueue(Question $question)
    {
        if (isset($question->options) === false) {
            return;
        }

        $options = json_decode($question->options);
        if (isset($options->makes_next_required) === false) {
            return;
        }
        for ($i = 0; $i < $options->makes_next_required->next; $i++) {
            array_push(
                $this->mandatorityQueue,
                [
                    'question' => $question->id,
                    'answer' => $question->answers[$options->makes_next_required->answer - 1]->id
                ]
            );
        }

    }

    /**
     * (Integrate and) get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        // When a question could have several answers but none is given,
        // one compilation item is anyway created, with NULL answer.
        // This logic is required because HTML array fields (e.g. set of checkboxes),
        // are not sent when no value is selected (even "nullable"
        // validation flag is useless in this case).
        foreach (Question::all() as $question) {
            if ($this->has('q' . $question->id) === false) {
                $this->merge(['q' . $question->id => null]);
            }
        }

        return $this->all();
    }

}
