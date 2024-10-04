<?php

namespace PaymentSystem\ValueObjects;

use JsonSerializable;
use Stringable;
use Symfony\Component\Intl\Countries;

readonly class Country implements Stringable, JsonSerializable
{
    private string $country;

    public function __construct(string $country)
    {
        if (strlen($country) === 3) {
            if (is_numeric($country)) {
                $this->country = $country;
                return;
            }

            $country = Countries::getAlpha2Code($country);
        }

        $this->country = Countries::getNumericCode($country);

        $this->validate();
    }

    private function validate(): void
    {
        Countries::numericCodeExists($this->country) || throw new \RuntimeException('Country is not valid');
    }

    public function __toString(): string
    {
        return $this->country;
    }

    public function jsonSerialize(): string
    {
        return (string)$this;
    }
}