<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Infrastructure\Provider;

use Ergonode\Reader\Infrastructure\Processor\ReaderProcessorInterface;

/**
 */
class ReaderProcessorProvider
{
    /**
     * @var
     */
    private $readers = [];

    /**
     * @param string                   $key
     * @param ReaderProcessorInterface $reader
     */
    public function setReader(string $key, ReaderProcessorInterface $reader): void
    {
        $key = strtolower($key);

        $this->readers[$key] = $reader;
    }

    /**
     * @param string $extension
     *
     * @return ReaderProcessorInterface
     */
    public function getReader(string $extension): ReaderProcessorInterface
    {
        $extension = strtolower($extension);

        if (isset($this->readers[$extension])) {
            return $this->readers[$extension];
        }

        throw new \LogicException(\sprintf('can\'t find reader for extension %s', $extension));
    }
}
