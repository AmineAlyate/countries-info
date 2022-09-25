<?php

namespace Countries;

use Countries\DTO\Country;
use Countries\Repository\Countries\CountriesRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CountryService
{
    private CountriesRepository $countriesRepository;

    public function __construct(CountriesRepository $countryCacheManager)
    {
        $this->countriesRepository = $countryCacheManager;
    }

    public function getCurrencyCode(string $countryCode): string
    {
        $country = $this->getCountryByCode($countryCode);
        if (! $country instanceof Country) {
            return 'NA';
        }

        return $country->getCurrency();
    }

    public function getCountryByCode(string $countryCode): ?Country
    {
        return $this->getCountries()
            ->filter(function (Country $country) use ($countryCode) {
                return $country->getCode() === Str::upper($countryCode);
            })->first();
    }

    public function getCountries(): Collection
    {
        return $this->countriesRepository->getCountries();
    }
}
