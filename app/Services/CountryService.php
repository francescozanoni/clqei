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

    public function getCountries() : array
    {
        return $this->countries;
    }

    public function getCountryCodes() : array
    {
        return array_keys($this->countries);
    }

}
