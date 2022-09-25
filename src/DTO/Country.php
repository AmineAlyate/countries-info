<?php

namespace Countries\DTO;

use Illuminate\Contracts\Support\Arrayable;

class Country implements Arrayable
{
    private string $name;
    private string $code;
    private string $currency;
    private string $isoCode;
    private array $cities;
    private ?array $languages;

    public function __construct(
        string $code,
        string $isoCode,
        string $name,
        string $currency,
        array $cities,
        ?array $languages
    ) {
        $this->code = $code;
        $this->isoCode = $isoCode;
        $this->name = $name;
        $this->currency = $currency;
        $this->cities = $cities;
        $this->languages = $languages;
    }

    public function toArray(): array
    {
        return [
            'name'      => $this->getName(),
            'code'      => $this->getCode(),
            'isoCode'   => $this->getIsoCode(),
            'currency'  => $this->getCurrency(),
            'languages' => $this->getLanguages(),
            'cities'    => $this->getCities(),
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getIsoCode(): string
    {
        return $this->isoCode;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getLanguages(): ?array
    {
        return $this->languages;
    }

    public function getCities(): array
    {
        return $this->cities;
    }
}
