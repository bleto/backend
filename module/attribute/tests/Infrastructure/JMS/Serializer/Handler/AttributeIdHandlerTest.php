<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Attribute\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Infrastructure\JMS\Serializer\Handler\AttributeIdHandler;
use JMS\Serializer\Context;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeIdHandlerTest extends TestCase
{
    /**
     * @var AttributeIdHandler
     */
    private $handler;

    /**
     * @var SerializationVisitorInterface
     */
    private $serializerVisitor;

    /**
     * @var DeserializationVisitorInterface
     */
    private $deserializerVisitor;
    /**
     * @var Context
     */
    private $context;

    /**
     */
    protected function setUp(): void
    {
        $this->handler = new AttributeIdHandler();
        $this->serializerVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializerVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = AttributeIdHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            $this->assertArrayHasKey('direction', $configuration);
            $this->assertArrayHasKey('type', $configuration);
            $this->assertArrayHasKey('format', $configuration);
            $this->assertArrayHasKey('method', $configuration);
        }
    }

    /**
     */
    public function testSerialize(): void
    {
        $id = AttributeId::generate();
        $result = $this->handler->serialize($this->serializerVisitor, $id, [], $this->context);

        $this->assertEquals($id->getValue(), $result);
    }

    /**
     */
    public function testDeserialize(): void
    {
        $id = AttributeId::generate();
        $result = $this->handler->deserialize($this->deserializerVisitor, $id->getValue(), [], $this->context);

        $this->assertEquals($id, $result);
    }
}