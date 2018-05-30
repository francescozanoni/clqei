<?php
declare(strict_types = 1);

namespace App\Services;

use App\Models\Answer;
use App\Models\Location;
use App\Models\Question;
use App\Models\Section;
use App\Models\Ward;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;

class CompilationService
{

    /**
     * @var Collection all questionnaire sections, also deleted
     */
    private $sections = null;

    /**
     * @var Collection all questionnaire questions, also deleted
     */
    private $questions = null;

    /**
     * @var Collection all questionnaire dynamic answers (i.e. based on database/seeds/*.json files), also deleted
     */
    private $answers = null;

    /**
     * @var array all questionnaire fixed questions (see __construct() method for details), also deleted
     */
    private $otherQuestions = [];

    /**
     * @var array all questionnaire "other" answers (see __construct() method for details), also deleted
     */
    private $otherAnswers = [];

    public function __construct()
    {
        $this->sections = Section::withTrashed()->get();
        $this->questions = Question::withTrashed()->get();
        $this->answers = Answer::withTrashed()->get();

        // Questions whose answers are located on other tables and that are not derived from database/seeds/*.json files
        $this->otherQuestions = [
            'stage_location_id' => __('Location'),
            'stage_ward_id' => __('Ward'),
            'stage_start_date' => __('Start date'),
            'stage_end_date' => __('End date'),
            'stage_academic_year' => __('Academic year'),
            'stage_weeks' => __('Weeks'),
            'student.gender' => __('Gender'),
            'student.nationality' => __('Nationality'),
        ];

        // Answers located on other tables
        $this->otherAnswers['__stage_locations__'] = Location::withTrashed()->get();
        $this->otherAnswers['__stage_wards__'] = Ward::withTrashed()->get();

        // Answers not located on tables
        // @todo handles case of deleted country
        $this->otherAnswers['__student_nationalities__'] = App::make('App\Services\CountryService')->getCountries();
    }

    /**
     * Detect whether all environment requirements
     * to create a compilation are met
     *
     * @return bool
     */
    public function isCompilationCreatable() : bool
    {
        return
            Location::count() > 0 &&
            Ward::count() > 0;
    }

    /**
     * Get section text from ID.
     *
     * @param $id
     * @return string
     */
    public function getSectionText($id) : string
    {
        $section = $this->sections->where('id', $id)->first();

        if ($section) {
            return $section->text;
        }

        return (string)$id;
    }

    /**
     * Get question text from ID (e.g. "23" or "q23").
     *
     * @param string|int $id
     * @return string
     */
    public function getQuestionText($id) : string
    {

        if (isset($this->otherQuestions[$id]) === true) {
            return $this->otherQuestions[$id];
        }

        $id = (string)$id;

        $question = $this->questions->where('id', preg_replace('/^q/', '', $id))->first();

        if ($question) {
            return $question->text;
        }

        return $id;
    }

    /**
     * Get answer text from ID, if any.
     *
     * @param string|int $answerId
     * @param string $questionId context of the answer to search, in case it belongs to a different table
     * @return string
     *
     * @todo refactor
     */
    public function getAnswerText($answerId, string $questionId = '') : string
    {
        switch ($questionId) {

            case 'stage_location_id':
                $answer = $this->otherAnswers['__stage_locations__']->where('id', $answerId)->first();
                return $answer->name;
                break;

            case 'stage_ward_id':
                $answer = $this->otherAnswers['__stage_wards__']->where('id', $answerId)->first();
                return $answer->name;
                break;

            case 'stage_weeks':
                return (string)$answerId;
                break;

            case 'student.gender':
                return __($answerId);
                break;

            case 'student.nationality':
                return $this->otherAnswers['__student_nationalities__'][$answerId];
                break;

            default:
                $answer = $this->answers->where('id', $answerId)->first();
                if ($answer) {
                    return __($answer->text);
                }
                return (string)$answerId;

        }

    }

}
