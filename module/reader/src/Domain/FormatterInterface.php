<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Domain;

/**
 */
interface FormatterInterface
{
    /**
     * @param string $string
     *
     * @return string
     */
    public function format(string $string): string;
}
