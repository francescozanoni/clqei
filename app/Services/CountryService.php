<?php

namespace App\Services;

class CountryService
{
  
  private $countries;
  
  public function __construct(array $countries)
  {
    $this->countries = $countries;
  }
  
  public function getCountries()
  {
    return $this->countries;
  }
  
}
