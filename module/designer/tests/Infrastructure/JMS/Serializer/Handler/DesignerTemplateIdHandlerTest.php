<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Designer\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Designer\Infrastructure\JMS\Serializer\Handler\TemplateIdHandler;
use JMS\Serializer\Context;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class DesignerTemplateIdHandlerTest extends TestCase
{
    /**
     * @var TemplateIdHandler
     */
    private $handler;

    /**
     * @var SerializationVisitorInterface
     */
    private $serializeVisitor;

    /**
     * @var DeserializationVisitorInterface
     */
    private $deserializeVisitor;

    /**
     * @var Context
     */
    private $context;

    /**
     */
    protected function setUp(): void
    {
        $this->handler = new TemplateIdHandler();
        $this->serializeVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializeVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = TemplateIdHandler::getSubscribingMethods();
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
        $id = TemplateId::generate();
        $result = $this->handler->serialize($this->serializeVisitor, $id, [], $this->context);

        $this->assertEquals($id->getValue(), $result);
    }

    /**
     */
    public function testDeserialize(): void
    {
        $id = TemplateId::generate();
        $result = $this->handler->deserialize($this->deserializeVisitor, $id->getValue(), [], $this->context);

        $this->assertEquals($id, $result);
    }
}
