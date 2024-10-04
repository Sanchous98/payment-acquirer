<?php

namespace PaymentSystem\ValueObjects;

use App\Infrastructure\ThreeDSIntegrator\Enum\ECICodesEnum;
use App\Infrastructure\ThreeDSIntegrator\Enum\ThreeDSStatusEnum;
use JsonSerializable;
use PaymentSystem\Enum\SupportedVersionsEnum;

readonly class ThreeDSResult implements JsonSerializable
{
    public function __construct(
        public ThreeDSStatusEnum $status,
        public string $authenticationValue,
        public ECICodesEnum $eci,
        public string $dsTransactionId,
        public string $acsTransactionId,
        public ?string $cardToken,
        public SupportedVersionsEnum $version,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'status' => $this->status->value,
            'authenticationValue' => $this->authenticationValue,
            'eci' => $this->eci->value,
            'dsTransactionId' => (string)$this->dsTransactionId,
            'acsTransactionId' => (string)$this->acsTransactionId,
            'cardToken' => $this->cardToken,
            'version' => $this->version->value,
        ];
    }
}
