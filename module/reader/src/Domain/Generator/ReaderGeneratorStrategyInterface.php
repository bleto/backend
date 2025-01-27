<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Reader\Domain\Generator;

use Ergonode\Reader\Domain\Entity\Reader;

/**
 */
interface ReaderGeneratorStrategyInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return Reader
     */
    public function generate(): Reader;
}
