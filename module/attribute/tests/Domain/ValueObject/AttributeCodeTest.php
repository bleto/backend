<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Attribute\Tests\Domain\ValueObject;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeCodeTest extends TestCase
{
    /**
     * @param string $value
     *
     * @dataProvider validDataProvider
     */
    public function testValidCharactersValue(string $value): void
    {
        $attributeCode = new AttributeCode($value);
        $this->assertEquals($value, $attributeCode->getValue());
    }

    /**
     * @param string $value
     *
     * @dataProvider invalidDataProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCharactersValue(string $value): void
    {
        $attributeCode = new AttributeCode($value);
        $this->assertEquals($value, $attributeCode->getValue());
    }

    /**
     * @return \Generator
     */
    public function validDataProvider(): \Generator
    {
        $collection = str_split('abcdefghijklmnopqrstuvwxyz1234567890_');
        foreach ($collection as $element) {
            yield ['abcd'.$element];
        }
    }

    /**
     * @return \Generator
     */
    public function invalidDataProvider(): \Generator
    {
        $collection = str_split('@#$%^&*()+={}[]:;"/?,.<>~`');
        foreach ($collection as $element) {
            yield ['abcd'.$element];
        }
    }
}
