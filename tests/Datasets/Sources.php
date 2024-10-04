<?php

use PaymentSystem\Contracts\SourceInterface;

class Cash implements SourceInterface
{
    public const TYPE = 'cash';
}

dataset('source', function () {
    yield new Cash();
});