<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\ValueObject;

/**
 * Class Sku
 */
class Sku
{
    private const LENGTH = 255;

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $value = \trim($value);

        if (!self::isValid($value)) {
            throw new \InvalidArgumentException(\sprintf('Sku "%s" is incorrect', $value));
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(?string $value): bool
    {
        return \strlen($value) <= self::LENGTH;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
