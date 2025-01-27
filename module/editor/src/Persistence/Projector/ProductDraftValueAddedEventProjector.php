<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\EventSourcing\Infrastructure\Exception\ProjectorException;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Editor\Domain\Event\ProductDraftValueAdded;
use Ergonode\Value\Domain\ValueObject\CollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableCollectionValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductDraftValueAddedEventProjector implements DomainEventProjectorInterface
{
    private const DRAFT_VALUE_TABLE = 'designer.draft_value';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param DomainEventInterface $event
     *
     * @return bool
     */
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof ProductDraftValueAdded;
    }

    /**
     * @param AbstractId           $aggregateId
     * @param DomainEventInterface $event
     *
     * @throws ProjectorException
     * @throws UnsupportedEventException
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof ProductDraftValueAdded) {
            throw new UnsupportedEventException($event, ProductDraftValueAdded::class);
        }

        try {
            $this->connection->beginTransaction();
            $draftId = $aggregateId->getValue();
            $elementId = AttributeId::fromKey($event->getAttributeCode())->getValue();

            $value = $event->getTo();

            $this->insertValue($draftId, $elementId, $value);
            $this->connection->commit();
        } catch (\Exception $exception) {
            $this->connection->rollBack();
            throw new ProjectorException($event, $exception);
        }
    }

    /**
     * @param string         $draftId
     * @param string         $elementId
     * @param ValueInterface $value
     *
     * @throws DBALException
     */
    private function insertValue(string $draftId, string $elementId, ValueInterface $value): void
    {
        if ($value instanceof StringValue) {
            $this->insert($draftId, $elementId, $value->getValue());
        } elseif ($value instanceof CollectionValue) {
            foreach ($value->getValue() as $phrase) {
                $this->insert($draftId, $elementId, $phrase);
            }
        } elseif ($value instanceof TranslatableStringValue) {
            $translation = $value->getValue();
            foreach ($translation as $language => $phrase) {
                $this->insert($draftId, $elementId, $phrase, $language);
            }
        } elseif ($value instanceof TranslatableCollectionValue) {
            $collection = $value->getValue();
            foreach ($collection as $translation) {
                foreach ($translation as $language => $phrase) {
                    $this->insert($draftId, $elementId, $phrase, $language);
                }
            }
        } else {
            throw new \RuntimeException(sprintf(sprintf('Unknown Value class "%s"', \get_class($value->getValue()))));
        }
    }

    /**
     * @param string      $draftId
     * @param string      $elementId
     * @param string      $value
     * @param string|null $language
     *
     * @throws DBALException
     * @throws \Exception
     */
    private function insert(string $draftId, string $elementId, string $value, string $language = null): void
    {
        $this->connection->insert(
            self::DRAFT_VALUE_TABLE,
            [
                'id' => Uuid::uuid4()->toString(),
                'draft_id' => $draftId,
                'element_id' => $elementId,
                'value' => $value,
                'language' => $language ?: null,
            ]
        );
    }
}
