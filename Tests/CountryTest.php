<?php

use Illuminate\Support\Collection;
use Countries\CountryService;

test('fetch countries test', function () {
    /** @var Collection $currencies */
    $currencies = app(CountryService::class)->allCountries();

    expect($currencies instanceof Collection)->toBeTrue();
    expect($currencies->isNotEmpty())->toBeTrue();
});

test('fetch country cities', function () {
    /** @var Collection $cities */
    $cities = app(CountryService::class)->getCities('MAR');

    expect($cities instanceof Collection)->toBeTrue();
    expect($cities->isNotEmpty())->toBeTrue();
});
