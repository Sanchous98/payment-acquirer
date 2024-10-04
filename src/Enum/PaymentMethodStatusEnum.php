<?php

namespace PaymentSystem\Enum;

enum PaymentMethodStatusEnum: string
{
    case PENDING = 'pending';
    case FAILED = 'failed';
    case SUCCEEDED = 'succeeded';
    case SUSPENDED = 'suspended';
}
