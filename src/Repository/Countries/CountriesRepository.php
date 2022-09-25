<?php

namespace Countries\Repository\Countries;

use Countries\DTO\Country;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Predis\Client;

class CountriesRepository
{
    private const COUNTRIES_CACHE_KEY = 'countries:all';
    private Client $redis;

    public function __construct()
    {
        $this->redis = new Client(
            [
                'scheme' => 'tcp',
                'host'   => '172.18.0.8',
                'port'   => 6379,
            ]
        );
    }

    public function getCountries(): Collection
    {
        $countries = json_decode($this->redis->get(self::COUNTRIES_CACHE_KEY), true);

        if ($countries instanceof Collection && $countries->count() > 0) {
            return $countries;
        }

        $countries = $this->loadCountries();

        $this->redis->set(self::COUNTRIES_CACHE_KEY, json_encode($countries));

        return $countries;
    }

    private function loadCountries(): Collection
    {
        $content = file_get_contents(__DIR__ . "/../../Data/countries.json");

        return Collection::make(json_decode($content, true))
            ->map(function ($country, $key) {
                $countryNames = Arr::get($country, 'name');
                $countryCode = Arr::get($country, 'wb_a3', $key);
                $currencies = Arr::get($country, 'currencies');
                $cities = $this->loadCities($countryCode);

                return new Country(
                    $countryCode,
                    Arr::get($country, 'cca3', $key),
                    Arr::get($countryNames, 'common', 'NA'),
                    Arr::get($currencies, 0, ''),
                    $cities,
                    Arr::get($country, 'languages'),
                );
            })
            ->values();
    }

    private function loadCities(string $countryCode): array
    {
        $file = __DIR__ . sprintf("/../../Data/cities/%s.json", Str::lower($countryCode));
        if (! file_exists($file)) {
            return [];
        }

        return Collection::make(json_decode(file_get_contents($file), true))->keys()->toArray();
    }
}
