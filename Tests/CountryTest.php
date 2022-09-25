<?php

use Countries\CountryService;
use Countries\DTO\Country;
use Countries\Repository\Countries\CountriesRepository;

test('fetch countries test', function () {
    $repository = new CountriesRepository();
    $countryService = new CountryService($repository);

    $countries = $countryService->getCountries();
    expect($countries->isNotEmpty())->toBeTrue();
});

test('fetch country cities', function () {
    $repository = new CountriesRepository();
    $countryService = new CountryService($repository);

    $country = $countryService->getCountryByCode('MAR');
    expect($country instanceof Country)->toBeTrue();
    expect(count($country->getCities()) > 0)->toBeTrue();
});

test('fetch country currency', function () {
    $repository = new CountriesRepository();
    $countryService = new CountryService($repository);

    $currency = $countryService->getCurrencyCode('MAR');
    expect($currency === 'MAD')->toBeTrue();
});
