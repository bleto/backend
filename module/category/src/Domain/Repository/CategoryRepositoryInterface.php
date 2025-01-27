<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Category\Domain\Entity\CategoryId;

/**
 */
interface CategoryRepositoryInterface
{
    /**
     * @param CategoryId $id
     *
     * @return AbstractAggregateRoot|Category
     */
    public function load(CategoryId $id): ?AbstractAggregateRoot;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;

    /**
     * @param CategoryId $id
     *
     * @return bool
     */
    public function exists(CategoryId $id): bool;
}
