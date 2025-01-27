<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Designer\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Designer\Domain\Entity\TemplateGroupId;
use Ergonode\Designer\Infrastructure\JMS\Serializer\Handler\TemplateGroupIdHandler;
use JMS\Serializer\Context;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use JMS\Serializer\VisitorInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class DesignerTemplateGroupIdHandlerTest extends TestCase
{
    /**
     * @var TemplateGroupIdHandler
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
        $this->handler = new TemplateGroupIdHandler();
        $this->serializeVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializeVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = TemplateGroupIdHandler::getSubscribingMethods();
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
        $id = TemplateGroupId::generate();
        $result = $this->handler->serialize($this->serializeVisitor, $id, [], $this->context);

        $this->assertEquals($id->getValue(), $result);
    }

    /**
     */
    public function testDeserialize(): void
    {
        $id = TemplateGroupId::generate();
        $result = $this->handler->deserialize($this->deserializeVisitor, $id->getValue(), [], $this->context);

        $this->assertEquals($id, $result);
    }
}
