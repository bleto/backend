<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\ColumnInterface;

/**
 */
interface AttributeColumnStrategyInterface
{
    /**
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function isSupported(AbstractAttribute $attribute): bool;

    /**
     * @param AbstractAttribute $attribute
     * @param Language          $language
     * @param array             $filter
     *
     * @return ColumnInterface
     */
    public function create(AbstractAttribute $attribute, Language $language, array $filter = []): ColumnInterface;
}
