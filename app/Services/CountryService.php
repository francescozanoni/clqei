<?php
declare(strict_types = 1);

namespace App\Services;

class CountryService
{

    /**
     * @var array
     */
    private $countries;

    public function __construct(array $countries)
    {
        $this->countries = $countries;
    }

    /**
     * Get all localized country names, indexed by ISO country codes.
     *
     * @return array e.g. Array (
     *                      [AF] => Afghanistan
     *                      [AL] => Albania
     *                      [DZ] => Algeria
     *                      [...]
     *                    )
     */
    public function getCountries() : array
    {
        return $this->countries;
    }

    /**
     * Get all ISO country codes.
     *
     * @return array
     */
    public function getCountryCodes() : array
    {
        return array_keys($this->countries);
    }

}
