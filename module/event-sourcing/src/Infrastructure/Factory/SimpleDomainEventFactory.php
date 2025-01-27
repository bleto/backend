<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Factory;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\EventSourcing\Infrastructure\DomainEventFactoryInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\SerializerInterface;

/**
 */
class SimpleDomainEventFactory implements DomainEventFactoryInterface
{
    /**
     * @var SerializerInterface;
     */
    private $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param AbstractId $id
     * @param array      $records
     *
     * @return DomainEventEnvelope[]
     */
    public function create(AbstractId $id, array $records): array
    {
        $result = [];
        foreach ($records as $record) {
            $result[] = $this->createElement($id, $record);
        }

        return $result;
    }

    /**
     * @param AbstractId $id
     * @param array      $record
     *
     * @return DomainEventEnvelope
     */
    private function createElement(AbstractId $id, array $record): DomainEventEnvelope
    {
        return new DomainEventEnvelope(
            $id,
            $record['sequence'],
            $this->getEvent($record['event'], $record['payload']),
            \DateTime::createFromFormat('Y-m-d H:i:s', $record['recorded_at'])
        );
    }

    /**
     * @param string $class
     * @param string $data
     *
     * @return mixed
     */
    private function getEvent(string $class, string $data): DomainEventInterface
    {
        return $this->serializer->deserialize($data, $class, 'json');
    }
}
