<?php

namespace Countries\DTO;

use Illuminate\Contracts\Support\Arrayable;

class Country implements Arrayable
{
    private string $name;
    private string $code;
    private string $currency;
    private string $isoCode;
    private ?array $languages;

    public function __construct(
        string $code,
        string $isoCode,
        string $name,
        string $currency,
        ?array $languages
    )
    {
        $this->code = $code;
        $this->isoCode = $isoCode;
        $this->name = $name;
        $this->currency = $currency;
        $this->languages = $languages;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getIsoCode(): string
    {
        return $this->isoCode;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getLanguages(): ?array
    {
        return $this->languages;
    }

    public function toArray(): array
    {
        return [
            'code'     => $this->getCode(),
            'name'     => $this->getName(),
            'currency' => $this->getCurrency()
        ];
    }
}