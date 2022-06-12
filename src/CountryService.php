<?php

namespace Countries;

use Countries\Cache\CountryCacheManager;
use Countries\DTO\Country;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CountryService
{
    private CountryCacheManager $countryCacheManager;

    public function __construct()
    {
        $this->countryCacheManager = app(CountryCacheManager::class);
    }

    public function allCountries(): Collection
    {
        $countries = $this->countryCacheManager->getAllCountries();
        if ($countries !== null) {
            return $countries;
        }

        $countries = $this->getCountries();
        $this->countryCacheManager->setAllCountries($countries);

        return $countries;
    }

    public function getCurrencyCode(string $countryCode): string
    {
        $country = $this->getCountryByCode($countryCode);
        if (! $country instanceof Country) {
            return 'NA';
        }

        return Arr::get($country->toArray(), 'currency', 'NA');
    }

    public function getCurrencies(): Collection
    {
        $currencies = $this->countryCacheManager->getCurrencies();
        if ($currencies !== null) {
            return $currencies;
        }

        $currencies = Collection::make(
            json_decode(file_get_contents(__DIR__ . sprintf("/Data/currencies/currencies.json")), true)
        );
        $this->countryCacheManager->setCurrencies($currencies);

        return $currencies;
    }

    public function getCities(string $countryCode): ?Collection
    {
        $cities = $this->countryCacheManager->getCitiesByCountry($countryCode);
        if (! is_null($cities)) {
            return $cities;
        }

        $file = __DIR__ . sprintf("/Data/cities/%s.json", Str::lower($countryCode));
        if (! file_exists($file)) {
            return null;
        }

        $cities = Collection::make(json_decode(file_get_contents($file), true))->keys();
        $this->countryCacheManager->setCitiesByCountry($countryCode, $cities);

        return $cities;
    }

    public function getCountryByCode(string $countryCode): ?Country
    {
        return $this->allCountries()
            ->filter(function (Country $country) use ($countryCode) {
                return $country->getCode() === Str::upper($countryCode);
            })->first();
    }

    private function getUnsupportedCountries(): array
    {
        return [];
    }

    private function getCountries(): Collection
    {
        $content = file_get_contents(__DIR__ . "/Data/countries.json");

        return Collection::make(json_decode($content, true))
            ->map(function ($country, $key) {
                $countryName = Arr::get($country, 'name');
                $currencies = Arr::get($country, 'currencies');

                return new Country(
                    Arr::get($country, 'cca2', $key),
                    Arr::get($country, 'cca3', $key),
                    Arr::get($countryName, 'common', 'NA'),
                    Arr::get($currencies, 0, ''),
                    Arr::get($country, 'languages'),
                );
            })
            ->reject(function ($country, $key) {
                return in_array(Arr::get($country, 'cca2', $key), $this->getUnsupportedCountries(), true);
            })
            ->values();
    }
}
