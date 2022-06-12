<?php

use Illuminate\Support\Collection;
use Countries\CountryService;

test('fetch currencies test', function () {
    /** @var Collection $currencies */
    $currencies = app(CountryService::class)->getCurrencies();

    expect($currencies instanceof Collection)->toBeTrue();
    expect($currencies->isNotEmpty())->toBeTrue();
});
