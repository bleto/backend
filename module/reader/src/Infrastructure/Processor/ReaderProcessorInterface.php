<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Infrastructure\Processor;

/**
 */
interface ReaderProcessorInterface extends \IteratorAggregate, \Countable
{
    /**
     * @param string $file
     * @param array  $configuration
     * @param array  $formatters
     */
    public function open(string $file, array $configuration = [], array $formatters = []): void;

    /**
     *
     */
    public function read(): \Traversable;

    /**
     */
    public function close(): void;
}
