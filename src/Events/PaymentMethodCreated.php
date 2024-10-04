<?php

namespace PaymentSystem\Events;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use PaymentSystem\Enum\SourceEnum;
use PaymentSystem\ValueObjects\BillingAddress;
use PaymentSystem\ValueObjects\Country;
use PaymentSystem\ValueObjects\Email;
use PaymentSystem\ValueObjects\Source;

readonly final class PaymentMethodCreated implements SerializablePayload
{
    public function __construct(
        public BillingAddress $billingAddress,
        public Source $source,
    ) {
    }

    public function toPayload(): array
    {
        return [
            'billing_address' => $this->billingAddress->jsonSerialize(),
            'source' => $this->source->jsonSerialize(),
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            billingAddress: new BillingAddress(
                firstName: $payload['billing_address']['first_name'],
                lastName: $payload['billing_address']['last_name'],
                city: $payload['billing_address']['city'],
                country: new Country($payload['billing_address']['country']),
                postalCode: $payload['billing_address']['postal_code'],
                email: new Email($payload['billing_address']['email']),
                phone: $payload['billing_address']['phone'],
                addressLine: $payload['billing_address']['address_line'],
                addressLineExtra: $payload['billing_address']['address_line_extra'] ?? '',
                state: $payload['billing_address']['state'] ?? '',
            ),
            source: Source::fromArray(
                SourceEnum::from($payload['source']['type']),
                $payload['source'][$payload['source']['type']],
            ),
        );
    }
}