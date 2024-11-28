<?php

namespace PaymentSystem;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Snapshotting\AggregateRootWithSnapshotting;
use EventSauce\EventSourcing\Snapshotting\Snapshot;
use PaymentSystem\Events\TokenCreated;
use PaymentSystem\Events\TokenUsed;
use PaymentSystem\Exceptions\TokenAlreadyUsedException;
use PaymentSystem\ValueObjects\CreditCard;

class TokenAggregateRoot implements AggregateRootWithSnapshotting
{
    use AggregateRootBehaviour;
    use SnapshotBehaviour;

    private CreditCard $card;

    private bool $used = false;

    public static function create(AggregateRootId $id, CreditCard $card): static
    {
        $self = new self($id);
        $self->recordThat(new TokenCreated($card));

        return $self;
    }

    public function use(): static
    {
        if ($this->used) {
            throw new TokenAlreadyUsedException();
        }

        $this->recordThat(new TokenUsed());

        return $this;
    }

    protected function applyTokenUsed(TokenUsed $tokenUsed): void
    {
        $this->used = true;
    }

    protected function applyTokenCreated(TokenCreated $event): void
    {
        $this->card = $event->card;
    }

    protected function applySnapshot(Snapshot $snapshot): void
    {
        $this->card = CreditCard::fromArray($snapshot->state()['card']);
        $this->used = (bool)$snapshot->state()['used'];
    }

    protected function createSnapshotState(): array
    {
        return [
            'card' => $this->card,
            'used' => $this->used,
        ];
    }
}