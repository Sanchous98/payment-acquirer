<?php

namespace PaymentSystem;

use EventSauce\EventSourcing\AggregateRootBehaviour;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Snapshotting\AggregateRootWithSnapshotting;
use EventSauce\EventSourcing\Snapshotting\Snapshot;
use PaymentSystem\Commands\CreateTokenCommandInterface;
use PaymentSystem\Contracts\SourceInterface;
use PaymentSystem\Events\TokenCreated;
use PaymentSystem\Events\TokenRevoked;
use PaymentSystem\Events\TokenUsed;
use PaymentSystem\Exceptions\CardExpiredException;
use PaymentSystem\Exceptions\TokenExpiredException;
use PaymentSystem\ValueObjects\CreditCard;

class TokenAggregateRoot implements AggregateRootWithSnapshotting
{
    use AggregateRootBehaviour;
    use SnapshotBehaviour;

    private CreditCard $card;

    private bool $expired = false;

    public function isExpired(): bool
    {
        return $this->expired;
    }

    public function getCard(): CreditCard
    {
        return $this->card;
    }

    public static function create(CreateTokenCommandInterface $command): static
    {
        $command->getCard()->expired() && throw new CardExpiredException();

        $self = new static($command->getId());
        $self->recordThat(new TokenCreated($command->getCard()));

        return $self;
    }

    public function markAsUsed(): static
    {
        if ($this->expired) {
            throw new TokenExpiredException();
        }

        $this->recordThat(new TokenUsed());

        return $this;
    }

    public function markAsRevoked(): static
    {
        if ($this->expired) {
            throw new TokenExpiredException();
        }

        $this->recordThat(new TokenRevoked());

        return $this;
    }

    protected function applyTokenUsed(): void
    {
        $this->expired = true;
    }

    protected function applyTokenRevoked(): void
    {
        $this->expired = true;
    }

    protected function applyTokenCreated(TokenCreated $event): void
    {
        $this->card = $event->card;
    }

    protected function applySnapshot(Snapshot $snapshot): void
    {
        $this->card = CreditCard::fromArray($snapshot->state()['card']);
        $this->expired = $snapshot->state()['expired'];
    }

    protected function createSnapshotState(): array
    {
        return [
            'card' => $this->card,
            'expired' => $this->expired,
        ];
    }
}