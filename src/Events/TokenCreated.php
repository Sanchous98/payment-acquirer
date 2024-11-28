<?php

namespace PaymentSystem\Events;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use PaymentSystem\ValueObjects\CreditCard;

readonly final class TokenCreated implements SerializablePayload
{
    public function __construct(public CreditCard $card)
    {
    }

    public function toPayload(): array
    {
        return ['card' => $this->card->jsonSerialize()];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(CreditCard::fromArray($payload['card']));
    }
}