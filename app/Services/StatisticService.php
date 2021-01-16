<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Compilation;
use InvalidArgumentException;
use Traversable;

class StatisticService
{

    /**
     * @var CompilationService
     */
    private $compilationService;

    public function __construct(CompilationService $compilationService)
    {
        $this->compilationService = $compilationService;
    }

    /**
     * Export a set of compilation to a statistic-compliant format.
     *
     * @param array|Traversable $compilations
     *
     * @return array e.g. Array (
     *                      Array (
     *                        [stage_location_id] => 14
     *                        [stage_ward_id] => 47
     *                        [stage_academic_year] => 2017/2018
     *                        [stage_weeks] => 10
     *                        [student_gender] => female
     *                        [student_nationality] => IT
     *                        [q1] => 10
     *                        [q2] => 86
     *                        [q3] => 87
     *                        [q4] => 90
     *                        [q5] => 95
     *                        [q6] => 103
     *                        [q7] => 105
     *                        [q9] => 109
     *                        [q10] => 139
     *                        [q11] => 141
     *                        [q13] => 147
     *                        [q14] => 152
     *                        [q15] => 156
     *                        [q16] => 160
     *                        [q17] => 164
     *                        [q18] => 168
     *                        [q19] => 172
     *                        [q20] => 176
     *                        [q21] => 180
     *                        [q22] => 184
     *                        [q23] => 188
     *                        [q24] => 192
     *                        [q25] => 196
     *                        [q26] => 200
     *                        [q27] => 204
     *                        [q28] => 208
     *                        [q29] => 212
     *                        [q30] => 216
     *                        [q31] => 220
     *                        [q32] => 224
     *                        [q33] => 228
     *                        [q34] => 232
     *                        [q35] => 236
     *                      )
     *                      [...]
     *                    )
     */
    public function formatCompilations($compilations): array
    {

        if (is_array($compilations) === false &&
            ($compilations instanceof Traversable) === false) {
            throw new InvalidArgumentException("Compilation set must be an array or implement Traversable interface");
        }

        if ($compilations instanceof Traversable) {
            $compilations = iterator_to_array($compilations);
        }

        return array_map([$this, "formatCompilation"], $compilations);

    }

    /**
     * Export a compilation to a statistic-compliant format.
     *
     * @param Compilation $compilation
     *
     * @return array e.g. Array (
     *                      [stage_location_id] => 14
     *                      [stage_ward_id] => 47
     *                      [stage_academic_year] => 2017/2018
     *                      [stage_weeks] => 10
     *                      [student_gender] => female
     *                      [student_nationality] => IT
     *                      [q1] => 10
     *                      [q2] => 86
     *                      [q3] => 87
     *                      [q4] => 90
     *                      [q5] => 95
     *                      [q6] => 103
     *                      [q7] => 105
     *                      [q9] => 109
     *                      [q10] => 139
     *                      [q11] => 141
     *                      [q13] => 147
     *                      [q14] => 152
     *                      [q15] => 156
     *                      [q16] => 160
     *                      [q17] => 164
     *                      [q18] => 168
     *                      [q19] => 172
     *                      [q20] => 176
     *                      [q21] => 180
     *                      [q22] => 184
     *                      [q23] => 188
     *                      [q24] => 192
     *                      [q25] => 196
     *                      [q26] => 200
     *                      [q27] => 204
     *                      [q28] => 208
     *                      [q29] => 212
     *                      [q30] => 216
     *                      [q31] => 220
     *                      [q32] => 224
     *                      [q33] => 228
     *                      [q34] => 232
     *                      [q35] => 236
     *                    )
     */
    public function formatCompilation(Compilation $compilation): array
    {

        $formatted = [
            "stage_location_id" => $compilation->stage_location_id,
            "stage_ward_id" => $compilation->stage_ward_id,
            "stage_academic_year" => $compilation->stage_academic_year,
            "stage_weeks" => $compilation->stage_weeks,
            "student_gender" => $compilation->student->gender,
            "student_nationality" => $compilation->student->nationality,
        ];

        foreach ($compilation->items as $item) {
            // NULL answers are added during compilation creation for validation purposes.
            if ($item->answer !== null) {
                $formatted["q" . $item->question_id] = $item->answer;
            }
        }

        return $formatted;
    }

    /**
     * Count occurrences of each answer of each question of the provided compilations.
     *
     * @param array|Traversable $formattedCompilations
     *
     * @return array e.g. Array (
     *                      [stage_location_id] => Array (
     *                        [14] => 82
     *                        [21] => 11
     *                        [...]
     *                      )
     *                      [stage_ward_id] => Array (
     *                        [65] => 3
     *                        [3] => 7
     *                        [...]
     *                      )
     *                      [stage_academic_year] => Array (
     *                        [2017/2018] => 120
     *                        [2016/2017] => 1
     *                      )
     *                      [stage_weeks] => Array (
     *                        [8] => 11
     *                        [5] => 1
     *                        [...]
     *                      )
     *                      [student_gender] => Array (
     *                        [female] => 95
     *                        [male] => 26
     *                      )
     *                      [student_nationality] => Array (
     *                        [RO] => 4
     *                        [IT] => 111
     *                        [...]
     *                      )
     *                      [q1] => Array (
     *                        [5] => 35
     *                        [8] => 5
     *                        [...]
     *                      )
     *                      [q2] => Array (
     *                        [84] => 18
     *                        [85] => 12
     *                        [...]
     *                      )
     *                      [...]
     *                    )
     */
    public function getCounts($formattedCompilations): array
    {
        if (is_array($formattedCompilations) === false &&
            ($formattedCompilations instanceof Traversable) === false) {
            throw new InvalidArgumentException("Compilation set must be an array or implement Traversable interface");
        }

        $counts = [];

        foreach ($formattedCompilations as $formattedCompilation) {
            foreach ($formattedCompilation as $questionId => $answerId) {
                if (isset($counts[$questionId]) === false) {
                    $counts[$questionId] = [];
                }
                if (isset($counts[$questionId][$answerId]) === false) {
                    $counts[$questionId][$answerId] = 0;
                }
                $counts[$questionId][$answerId]++;
            }
        }

        // Answers are sorted by record ID.
        // @todo sort by answer real sort value
        foreach ($counts as $questionId => &$answers) {
            if (preg_match("/^q\d+$/", $questionId) === 1) {
                ksort($answers);
            }
        }

        return $counts;
    }

    /**
     * Switch statistics as counts to statistics as percentages.
     *
     * @param array $counts e.g. Array (
     *                                 [stage_location_id] => Array (
     *                                   [14] => 82
     *                                   [21] => 11
     *                                   [...]
     *                                 )
     *                                 [stage_ward_id] => Array (
     *                                   [65] => 3
     *                                   [3] => 7
     *                                   [...]
     *                                 )
     *                                 [stage_academic_year] => Array (
     *                                   [2017/2018] => 120
     *                                   [2016/2017] => 1
     *                                 )
     *                                 [stage_weeks] => Array (
     *                                   [8] => 11
     *                                   [5] => 1
     *                                   [...]
     *                                 )
     *                                 [student_gender] => Array (
     *                                   [female] => 95
     *                                   [male] => 26
     *                                 )
     *                                 [student_nationality] => Array (
     *                                   [RO] => 4
     *                                   [IT] => 111
     *                                   [...]
     *                                 )
     *                                 [q1] => Array (
     *                                   [5] => 35
     *                                   [8] => 5
     *                                   [...]
     *                                 )
     *                                 [q2] => Array (
     *                                   [84] => 18
     *                                   [85] => 12
     *                                   [...]
     *                                 )
     *                                 [...]
     *                               )
     *
     * @return array e.g. Array (
     *                      [stage_location_id] => Array (
     *                        [14] => 82
     *                        [21] => 11
     *                        [...]
     *                      )
     *                      [stage_ward_id] => Array (
     *                        [65] => 3
     *                        [3] => 7
     *                        [...]
     *                      )
     *                      [stage_academic_year] => Array (
     *                        [2017/2018] => 120
     *                        [2016/2017] => 1
     *                      )
     *                      [stage_weeks] => Array (
     *                        [8] => 11
     *                        [5] => 1
     *                        [...]
     *                      )
     *                      [student_gender] => Array (
     *                        [female] => 95
     *                        [male] => 26
     *                      )
     *                      [student_nationality] => Array (
     *                        [RO] => 4
     *                        [IT] => 111
     *                        [...]
     *                      )
     *                      [q1] => Array (
     *                        [5] => 35
     *                        [8] => 5
     *                        [...]
     *                      )
     *                      [q2] => Array (
     *                        [84] => 18
     *                        [85] => 12
     *                        [...]
     *                      )
     *                      [...]
     *                    )
     */
    public function switchCountsToPercentages(array $counts): array
    {

        $percentages = [];

        foreach ($counts as $questionId => $questionCounts) {
            $percentages[$questionId] = [];
            foreach ($questionCounts as $answerId => $answerCount) {
                $percentages[$questionId][$answerId] = $answerCount / array_sum($questionCounts);
            }
        }

        return $percentages;
    }

}
