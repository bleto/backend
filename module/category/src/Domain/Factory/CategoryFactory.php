<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Domain\Factory;

use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Webmozart\Assert\Assert;

/**
 */
class CategoryFactory
{
    /**
     * @param CategoryId         $id
     * @param CategoryCode       $code
     * @param TranslatableString $name
     * @param array              $attributes
     *
     * @return Category
     */
    public function create(
        CategoryId $id,
        CategoryCode $code,
        TranslatableString $name,
        array $attributes = []
    ): Category {

        Assert::allIsInstanceOf($attributes, ValueInterface::class);

        return new Category(
            $id,
            $code,
            $name,
            $attributes
        );
    }
}
