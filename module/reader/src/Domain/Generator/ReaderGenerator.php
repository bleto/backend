<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Domain\Generator;

use Ergonode\Reader\Domain\Entity\Reader;

/**
 */
class ReaderGenerator
{
    /**
     * @var ReaderGeneratorStrategyInterface[]
     */
    private $strategies;

    /**
     * @param ReaderGeneratorStrategyInterface ...$strategies
     */
    public function __construct(ReaderGeneratorStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param string $type
     *
     * @return Reader
     */
    public function generate(string $type): Reader
    {
        foreach ($this->strategies as $strategy) {
            if (strtoupper($type) === $strategy->getType()) {
                return $strategy->generate();
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find reader %s generator ', $type));
    }
}
