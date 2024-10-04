<?php

use PaymentSystem\Commands\CreateTokenCommandInterface;
use PaymentSystem\Events\TokenCreated;
use PaymentSystem\Exceptions\CardExpiredException;
use PaymentSystem\Tests\IntId;
use PaymentSystem\TokenAggregateRoot;
use PaymentSystem\ValueObjects\CreditCard;

test('token created successfully', function () {
    $command = $this->createStub(CreateTokenCommandInterface::class);
    $command->method('getId')->willReturn(new IntId(1));
    $command->method('getCard')->willReturn($card = new CreditCard(
        new CreditCard\Number('424242', '4242', 'visa'),
        new CreditCard\Expiration(12, 34),
        new CreditCard\Holder('Andrea Palladio'),
        new CreditCard\Cvc(),
    ));

    $token = TokenAggregateRoot::create($command);

    expect($token)
        ->isExpired()->toBeFalse()
        ->getCard()->toEqual($card);
});

test('expired card cannot be tokenized', function () {
    $expiration = new DateTimeImmutable('-1 month');

    $command = $this->createStub(CreateTokenCommandInterface::class);
    $command->method('getId')->willReturn(new IntId(1));
    $command->method('getCard')->willReturn(new CreditCard(
        new CreditCard\Number('424242', '4242', 'visa'),
        new CreditCard\Expiration($expiration->format('n'), $expiration->format('y')),
        new CreditCard\Holder('Andrea Palladio'),
        new CreditCard\Cvc(),
    ));

    TokenAggregateRoot::create($command);
})->throws(CardExpiredException::class);

test('token can be marked as used', function () {
    $token = TokenAggregateRoot::reconstituteFromEvents(new IntId(1), generator([
        new TokenCreated(new CreditCard(
            new CreditCard\Number('424242', '4242', 'visa'),
            new CreditCard\Expiration(12, 34),
            new CreditCard\Holder('Andrea Palladio'),
            new CreditCard\Cvc(),
        )),
    ]));

    $token->markAsUsed();

    expect($token)->isExpired()->toBeTrue();
});

test('token can be revoked', function () {
    $token = TokenAggregateRoot::reconstituteFromEvents(new IntId(1), generator([
        new TokenCreated(new CreditCard(
            new CreditCard\Number('424242', '4242', 'visa'),
            new CreditCard\Expiration(12, 34),
            new CreditCard\Holder('Andrea Palladio'),
            new CreditCard\Cvc(),
        )),
    ]));

    $token->markAsRevoked();

    expect($token)->isExpired()->toBeTrue();
});