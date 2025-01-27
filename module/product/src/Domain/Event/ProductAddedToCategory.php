<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductAddedToCategory implements DomainEventInterface
{
    /**
     * @var CategoryCode
     *
     * @JMS\Type("Ergonode\Category\Domain\ValueObject\CategoryCode")
     */
    private $categoryCode;

    /**
     * @param CategoryCode $categoryCode
     */
    public function __construct(CategoryCode $categoryCode)
    {
        $this->categoryCode = $categoryCode;
    }

    /**
     * @return CategoryCode
     */
    public function getCategoryCode(): CategoryCode
    {
        return $this->categoryCode;
    }
}
