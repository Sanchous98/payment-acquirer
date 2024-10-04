<?php

namespace PaymentSystem\Exceptions;

use PaymentSystem\Enum\PaymentIntentStatusEnum;
use PaymentSystem\Enum\RefundStatusEnum;
use RuntimeException;

class RefundUnavailableException extends RuntimeException
{
    public static function unsupportedIntentStatus(PaymentIntentStatusEnum $status): static
    {
        return new static("Cannot refund intent with status '$status->value'");
    }
}
