<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\ValueObject\OptionValue;

use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use JMS\Serializer\Annotation as JMS;

/**
 */
class MultilingualOption extends AbstractOption implements OptionInterface
{
    /**
     * @var string
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $value;

    /**
     * @param TranslatableString $value
     */
    public function __construct(TranslatableString $value)
    {
        $this->value = $value;
    }

    /**
     * @return TranslatableString
     */
    public function getValue(): TranslatableString
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isMultilingual(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return implode(',', $this->value->getTranslations());
    }

    /**
     * @param OptionInterface $value
     *
     * @return bool
     */
    public function equal(OptionInterface $value): bool
    {
        return $value instanceof self &&
            array_diff_assoc($value->getValue()->getTranslations(), $this->value->getTranslations()) === array_diff_assoc($this->value->getTranslations(), $value->getValue()->getTranslations());
    }
}
