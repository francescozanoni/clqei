<?php
declare(strict_types = 1);

namespace App\Services;

class AcademicYearService
{

    /**
     * Get the previous academic year
     *
     * @return string e.g. '2016/2017'
     */
    public function getPrevious() : string
    {
        return $this->getOther(-1);
    }

    /**
     * Get an academic year
     *
     * @param int $offset offset from the current academic year
     * @return string
     */
    public function getOther(int $offset = 0) : string
    {
        $current = $this->getCurrent();
        $firstPart = ((int)substr($current, 0, 4)) + $offset;
        $lastPart = ((int)substr($current, 5, 4)) + $offset;
        return ($firstPart) . '/' . ($lastPart);
    }

    /**
     * Get the current academic year
     *
     * @return string e.g. '2017/2018'
     */
    public function getCurrent() : string
    {
        $time = strtotime('now');
        $year = date('Y', $time);

        if (date('n', $time) < 10) {
            return ($year - 1) . '/' . $year;
        } else {
            return ($year) . '/' . ($year + 1);
        }
    }

    /**
     * Get the next academic year
     *
     * @return string e.g. '2018/2019'
     */
    public function getNext() : string
    {
        return $this->getOther(+1);
    }

}
