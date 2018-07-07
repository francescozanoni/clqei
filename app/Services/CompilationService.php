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
        $this->sections = new Collection();
        $sections = Section::withTrashed()->get();
        foreach ($sections as $section) {
            $this->sections->put($section->id, $section);
        }

        $this->questions = new Collection();
        $questions = Question::withTrashed()->get();
        foreach ($questions as $question) {
            $this->questions->put($question->id, $question);
        }

        $this->answers = new Collection();
        $answers = Answer::withTrashed()->get();
        foreach ($answers as $answer) {
            $this->answers->put($answer->id, $answer);
        }

        // Questions whose answers are located on other tables and that are not derived from database/seeds/*.json files
        $this->otherQuestions = [
            'stage_location_id' => __('Location'),
            'stage_ward_id' => __('Ward'),
            'stage_start_date' => __('Start date'),
            'stage_end_date' => __('End date'),
            'stage_academic_year' => __('Academic year'),
            'stage_weeks' => __('Weeks'),
            'student_gender' => __('Gender'),
            'student_nationality' => __('Nationality'),
        ];

        // Answers located on other tables
        $this->otherAnswers['__stage_locations__'] = new Collection();
        $answers = Location::withTrashed()->get();
        foreach ($answers as $answer) {
            $this->otherAnswers['__stage_locations__']->put($answer->id, $answer);
        }
        $this->otherAnswers['__stage_wards__'] = new Collection();
        $answers = Ward::withTrashed()->get();
        foreach ($answers as $answer) {
            $this->otherAnswers['__stage_wards__']->put($answer->id, $answer);
        }

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
     * @param int|string $id section ID
     * @return string
     */
    public function getSectionText($id) : string
    {
        $section = $this->sections->get($id);

        if ($section) {
            return $section->text;
        }

        return (string)$id;
    }

    /**
     * Get question text from ID (e.g. "23" or "q23").
     *
     * @param int|string $id question ID
     * @return string
     */
    public function getQuestionText($id) : string
    {

        if (isset($this->otherQuestions[$id]) === true) {
            return $this->otherQuestions[$id];
        }

        $id = (string)$id;

        $question = $this->questions->get(preg_replace('/^q/', '', $id));

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
      
        $text = $answerId;
      
        switch ($questionId) {

            case 'stage_location_id':
                $answer = $this->otherAnswers['__stage_locations__']->get($answerId);
                $text = $answer->name;
                break;

            case 'stage_ward_id':
                $answer = $this->otherAnswers['__stage_wards__']->get($answerId);
                $text = $answer->name;
                break;

            case 'stage_weeks':
                $text = $answerId;
                break;

            case 'student_gender':
                $text = __($answerId);
                break;

            case 'student_nationality':
                $text = $this->otherAnswers['__student_nationalities__'][$answerId];
                break;

            default:
                $answer = $this->answers->get($answerId);
                if ($answer) {
                    $text = __($answer->text);
                }
            
        }
      
        return (string)$text;

    }
    
    /**
     * Get question's section, if available.
     *
     * @param int|string $id question ID
     * @return null|App\Models\Section
     */
    public function getQuestionSection($id)
    {

        if (isset($this->otherQuestions[$id]) === true) {
            return null;
        }

        $id = (string)$id;

        $question = $this->questions->get(preg_replace('/^q/', '', $id));

        return $this->sections->get($question->section_id);
        
    }

}
