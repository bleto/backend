<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Product\Domain\Event\ProductValueChanged;
use Ergonode\Value\Domain\ValueObject\CollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableCollectionValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductValueChangedEventProjector implements DomainEventProjectorInterface
{
    private const NAMESPACE = 'cb2600df-94fb-4755-9e6a-a15591a8e510';
    private const TABLE_PRODUCT_VALUE = 'product_value';
    private const TABLE_VALUE_TRANSLATION = 'value_translation';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * ProductCreateEventProjector constructor.
     *
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
        return $event instanceof ProductValueChanged;
    }

    /**
     * @param AbstractId           $aggregateId
     * @param DomainEventInterface $event
     *
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Throwable
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof ProductValueChanged) {
            throw new UnsupportedEventException($event, ProductValueChanged::class);
        }

        $this->connection->beginTransaction();
        try {
            $productId = $aggregateId->getValue();
            $attributeId = AttributeId::fromKey($event->getAttributeCode())->getValue();

            $this->delete($productId, $attributeId);
            $this->insertValue($productId, $attributeId, $event->getTo());

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
    }

    /**
     * @param string         $productId
     * @param string         $attributeId
     * @param ValueInterface $value
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insertValue(string $productId, string $attributeId, ValueInterface $value): void
    {
        if ($value instanceof StringValue) {
            $this->insert($productId, $attributeId, $value->getValue());
        } elseif ($value instanceof CollectionValue) {
            foreach ($value->getValue() as $phrase) {
                $this->insert($productId, $attributeId, $phrase);
            }
        } elseif ($value instanceof TranslatableStringValue) {
            $translation = $value->getValue();
            foreach ($translation as $language => $phrase) {
                $this->insert($productId, $attributeId, $phrase, $language);
            }
        } elseif ($value instanceof TranslatableCollectionValue) {
            $collection = $value->getValue();
            foreach ($collection as $translation) {
                foreach ($translation as $language => $phrase) {
                    $this->insert($productId, $attributeId, $phrase, $language);
                }
            }
        } else {
            throw new \RuntimeException(sprintf(sprintf('Unknown Value class "%s"', \get_class($value->getValue()))));
        }
    }

    /**
     * @param string      $productId
     * @param string      $attributeId
     * @param string      $value
     * @param string|null $language
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(string $productId, string $attributeId, string $value, string $language = null): void
    {
        if ($value !== '') {
            $valueId = Uuid::uuid5(self::NAMESPACE, implode('|', [$value, $language]));

            $qb = $this->connection->createQueryBuilder();
            $result = $qb->select('*')
                ->from(self::TABLE_VALUE_TRANSLATION)
                ->where($qb->expr()->eq('id', ':id'))
                ->setParameter(':id', $valueId->toString())
                ->execute()
                ->fetch();

            if (false === $result) {
                $this->connection->executeQuery(
                    'INSERT INTO value_translation (id, value_id, value, language) VALUES (?, ?, ?, ?) ON CONFLICT DO NOTHING',
                    [$valueId->toString(), $valueId->toString(), $value, $language ?: null]
                );
            }

            $this->connection->insert(
                self::TABLE_PRODUCT_VALUE,
                [
                    'product_id' => $productId,
                    'attribute_id' => $attributeId,
                    'value_id' => $valueId,
                ]
            );
        }
    }

    /**
     * @param string $productId
     * @param string $attributeId
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    private function delete(string $productId, string $attributeId): void
    {
        $this->connection->delete(
            self::TABLE_PRODUCT_VALUE,
            [
                'product_id' => $productId,
                'attribute_id' => $attributeId,
            ]
        );
    }
}
