<?php
declare(strict_types = 1);

namespace App\Services;

use App\Models\Answer;
use App\Models\Location;
use App\Models\Question;
use App\Models\Section;
use App\Models\Ward;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class CompilationService
{

    /**
     * @var EloquentCollection all questionnaire sections, also deleted
     */
    private $sections = null;

    /**
     * @var EloquentCollection all questionnaire questions, also deleted
     */
    private $questions = null;

    /**
     * @var EloquentCollection all questionnaire dynamic answers (i.e. based on database/seeds/*.json files), also deleted
     */
    private $answers = null;

    /**
     * @var Collection all questionnaire fixed questions (see __construct() method for details), also deleted
     */
    private $otherQuestions = null;

    /**
     * @var array all questionnaire "other" answers (see __construct() method for details), also deleted
     */
    private $otherAnswers = [];

    public function __construct(CountryService $countryService)
    {
        $this->sections = Section::withTrashed()->get()->keyBy("id");
        $this->questions = Question::withTrashed()->get()->keyBy("id");
        $this->answers = Answer::withTrashed()->get()->keyBy("id");

        // Questions whose answers are located on other tables
        // and that are not derived from database/seeds/*.json files
        $this->otherQuestions = new Collection([
            "stage_location_id" => __("Location"),
            "stage_ward_id" => __("Ward"),
            "stage_start_date" => __("Start date"),
            "stage_end_date" => __("End date"),
            "stage_academic_year" => __("Academic year"),
            "stage_weeks" => __("Weeks"),
            "student_gender" => __("Gender"),
            "student_nationality" => __("Nationality"),
        ]);

        // Answers located on other tables
        $this->otherAnswers["__stage_locations__"] = Location::withTrashed()->get()->keyBy("id");
        $this->otherAnswers["__stage_wards__"] = Ward::withTrashed()->get()->keyBy("id");

        // Answers not located on tables
        // @todo handles case of deleted country
        $this->otherAnswers["__student_nationalities__"] = new Collection($countryService->getCountries());
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

        $otherQuestion = $this->otherQuestions->get($id);

        if ($otherQuestion) {
            return $otherQuestion;
        }

        $id = (string)$id;

        $question = $this->questions->get(preg_replace("/^q/", "", $id));

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
    public function getAnswerText($answerId, string $questionId = "") : string
    {

        $text = $answerId;

        switch ($questionId) {

            case "stage_location_id":
                $text = $this->otherAnswers["__stage_locations__"]->get($answerId)->name;
                break;

            case "stage_ward_id":
                $text = $this->otherAnswers["__stage_wards__"]->get($answerId)->name;
                break;

            case "student_gender":
                $text = __($answerId);
                break;

            case "student_nationality":
                $text = $this->otherAnswers["__student_nationalities__"]->get($answerId);
                break;

            default:
                if ($answer = $this->answers->get($answerId)) {
                    $text = __($answer->text);
                }

        }

        return (string)$text;

    }

    /**
     * Get question's section, if available.
     *
     * @param int|string $id question ID
     * @return null|\App\Models\Section
     */
    public function getQuestionSection($id)
    {

        if ($this->otherQuestions->has($id) === true) {
            return null;
        }

        $id = (string)$id;

        $question = $this->questions->get(preg_replace("/^q/", "", $id));

        return $this->sections->get($question->section_id);

    }

    /**
     * Apply filters to compilation search query.
     *
     * @param Builder $query
     * @param string $parameter
     * @param $value
     */
    public function applyQueryFilters(Builder $query, string $parameter, $value)
    {

        if (strpos($parameter, "stage_") === 0) {
            $this->applyQueryStageFilters($query, $parameter, $value);
        }

        if (strpos($parameter, "student_") === 0) {
            $parameter = str_replace("student_", "", $parameter);
            $this->applyQueryStudentFilters($query, $parameter, $value);
        }

        // Dynamic questions
        if (preg_match("/^q\d+$/", $parameter) === 1) {
            $parameter = str_replace("q", "", $parameter);
            $query->whereHas("items", function ($query) use ($parameter, $value) {
                $query->where("question_id", $parameter)
                    ->where("answer", $value);
            });
        }

    }

    /**
     * Apply stage-related filters to compilation search query.
     *
     * @param Builder $query
     * @param string $parameter
     * @param mixed $value
     */
    private function applyQueryStageFilters(Builder $query, string $parameter, $value)
    {

        switch ($parameter) {
            case "stage_location_id":
            case "stage_ward_id":
            case "stage_academic_year":
                $query->where($parameter, $value);
                break;
            case "stage_weeks":
                // @todo check whether this SQL string is compatible with other database engines
                $query->whereRaw(
                    "round((strftime('%J', stage_end_date) - strftime('%J', stage_start_date) + 1) / 7) = ?",
                    [(int)$value]
                );
                break;
            default:

        }
    }

    /**
     * Apply student-related filters to compilation search query.
     *
     * @param Builder $query
     * @param string $parameter
     * @param mixed $value
     */
    private function applyQueryStudentFilters(Builder $query, string $parameter, $value)
    {

        switch ($parameter) {
            case "gender":
            case "nationality":
                $query->whereHas("student", function ($query) use ($parameter, $value) {
                    $query->where($parameter, $value);
                });
                break;
            default:

        }
    }

}
