<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Core\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\JMS\Serializer\Handler\LanguageHandler;
use JMS\Serializer\Context;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class LanguageHandlerTest extends TestCase
{
    /**
     * @var LanguageHandler
     */
    private $handler;

    /**
     * @var SerializationVisitorInterface
     */
    private $serializationVisitor;

    /**
     * @var DeserializationVisitorInterface
     */
    private $deserializationVisitor;

    /**
     * @var Context
     */
    private $context;

    /**
     */
    protected function setUp(): void
    {
        $this->handler = new LanguageHandler();
        $this->serializationVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializationVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = LanguageHandler::getSubscribingMethods();
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
        $testValue = 'PL';
        $code = new Language($testValue);
        $result = $this->handler->serialize($this->serializationVisitor, $code, [], $this->context);

        $this->assertEquals($testValue, $result);
    }

    /**
     */
    public function testDeserialize(): void
    {
        $testValue = 'EN';
        $result = $this->handler->deserialize($this->deserializationVisitor, $testValue, [], $this->context);

        $this->assertEquals($testValue, $result->getCode());
    }
}
