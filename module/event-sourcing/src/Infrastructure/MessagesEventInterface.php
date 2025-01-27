<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure;

use Ergonode\Core\Domain\Entity\AbstractId;

/**
 */
interface MessagesEventInterface
{
    /**
     * @return AbstractId
     */
    public function getId(): AbstractId;
}
