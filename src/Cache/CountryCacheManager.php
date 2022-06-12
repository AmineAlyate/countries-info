<?php

namespace Countries\Cache;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Collection;

class CountryCacheManager
{
    private const COUNTRIES_CACHE_KEY = 'countries:%s';
    private const CITIES_CACHE_KEY = 'cities:%s';
    private const CURRENCIES_CACHE_KEY = 'currencies:%s';

    private ?Repository $cacheRepository;

    public function __construct()
    {
        $this->cacheRepository = null;
    }

    public function getAllCountries(): ?Collection
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        return $this->cacheRepository->get($this->getCountriesCacheKey('all'));
    }

    public function setAllCountries(Collection $countries): bool
    {
        if ($this->isCacheDisabled()) {
            return false;
        }

        return $this->cacheRepository->set($this->getCountriesCacheKey('all'), $countries, $this->getTTL());
    }

    public function setCitiesByCountry(string $countryCode, Collection $cities): bool
    {
        if ($this->isCacheDisabled()) {
            return false;
        }

        return $this->cacheRepository->set($this->getCitiesCacheKey('cities'), $cities, $this->getTTL());
    }

    public function getCitiesByCountry(string $countryCode): ?Collection
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        return $this->cacheRepository->get($this->getCitiesCacheKey($countryCode));
    }

    public function getCurrencies(): ?Collection
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        return $this->cacheRepository->get($this->getCurrenciesCacheKey('all'));
    }

    public function setCurrencies(Collection $cities): bool
    {
        if ($this->isCacheDisabled()) {
            return false;
        }

        return $this->cacheRepository->set($this->getCurrenciesCacheKey('all'), $cities, $this->getTTL());
    }

    private function getCountriesCacheKey(string $key): string
    {
        return sprintf(self::COUNTRIES_CACHE_KEY, $key);
    }

    private function getCitiesCacheKey(string $key): string
    {
        return sprintf(self::CITIES_CACHE_KEY, $key);
    }

    private function getCurrenciesCacheKey(string $key): string
    {
        return sprintf(self::CURRENCIES_CACHE_KEY, $key);
    }

    private function getTTL(): int
    {
        return 86400; // 24 hours
    }

    private function isCacheDisabled(): bool
    {
        return $this->cacheRepository === null;
    }
}