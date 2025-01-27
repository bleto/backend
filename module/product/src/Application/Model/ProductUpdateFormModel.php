<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Model;

/**
 */
class ProductUpdateFormModel
{
    /**
     * @var array
     */
    public $categories;

    /**
     */
    public function __construct()
    {
        $this->categories = [];
    }
}
