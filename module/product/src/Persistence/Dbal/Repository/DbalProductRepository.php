<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\DomainEventDispatcherInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\ProductSimple\Domain\Entity\SimpleProduct;

/**
 */
class DbalProductRepository implements ProductRepositoryInterface
{
    /**
     * @var DomainEventStoreInterface
     */
    private $eventStore;

    /**
     * @var DomainEventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param DomainEventStoreInterface      $eventStore
     * @param DomainEventDispatcherInterface $eventDispatcher
     */
    public function __construct(DomainEventStoreInterface $eventStore, DomainEventDispatcherInterface $eventDispatcher)
    {
        $this->eventStore = $eventStore;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param ProductId $id
     *
     * @return AbstractAggregateRoot
     * @throws \ReflectionException
     */
    public function load(ProductId $id): AbstractAggregateRoot
    {
        $eventStream = $this->eventStore->load($id);

        $class = new \ReflectionClass(SimpleProduct::class);
        /** @var AbstractAggregateRoot $aggregate */
        $aggregate = $class->newInstanceWithoutConstructor();
        if (!$aggregate instanceof AbstractAggregateRoot) {
            throw new \LogicException(sprintf('Impossible to initialize "%s"', SimpleProduct::class));
        }

        $aggregate->initialize($eventStream);

        return $aggregate;
    }

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void
    {
        $events = $aggregateRoot->popEvents();

        $this->eventStore->append($aggregateRoot->getId(), $events);
        foreach ($events as $envelope) {
            $this->eventDispatcher->dispatch($envelope);
        }
    }
}
