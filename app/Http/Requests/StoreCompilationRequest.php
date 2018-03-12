<?php
declare(strict_types = 1);

namespace App\Http\Requests;

use App;
use App\Models\Compilation;
use App\Models\Question;
use App\Services\AcademicYearService;
use Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompilationRequest extends FormRequest
{

    /**
     * @var array Queue reporting question mandatority depending on the answer of a previous question
     */
    private $mandatorityQueue = [];

    /**
     * @var AcademicYearService
     */
    private $academicYearService;

    /**
     * StoreCompilationRequest constructor.
     * @param array $query
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null|resource|string $content
     * @param AcademicYearService $academicYearService
     */
    public function __construct(
        array $query,
        array $request,
        array $attributes,
        array $cookies,
        array $files,
        array $server,
        $content,
        AcademicYearService $academicYearService
    ) {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->academicYearService = $academicYearService;
    }

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
     * @return array
     */
    public function rules()
    {

        // Stage end date must be before today or before 18 weeks after stage start date.
        // @todo move week number to configuration
        $maxEndDate = Carbon::parse($this->stage_start_date)->addWeeks(18);
        if (Carbon::today() < $maxEndDate) {
            $maxEndDate = Carbon::today()->format('Y-m-d');
        } else {
            $maxEndDate = $maxEndDate->format('Y-m-d');
        }

        $rules = [
            'student_id' => 'required|exists:students,id|in:' . Auth::user()->student->id,
            'stage_location_id' => 'required|exists:locations,id',
            'stage_ward_id' => 'required|exists:wards,id',
            // @todo add start/end date validation against academic year
            'stage_start_date' => 'required|date|before:today',
            'stage_end_date' => 'required|date|after:stage_start_date|before:' . $maxEndDate,
            'stage_academic_year' => 'required|in:' . implode(',', [
                    $this->academicYearService->getPrevious(),
                    $this->academicYearService->getCurrent()
                ])
        ];

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

}
