<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Command;

use Ergonode\Attribute\Domain\Entity\AttributeId;

/**
 * Class DeleteAttributeCommand
 */
class DeleteAttributeCommand
{
    /**
     * @var AttributeId
     */
    private $id;

    /**
     * @param AttributeId $id
     */
    public function __construct(AttributeId $id)
    {
        $this->id = $id;
    }

    /**
     * @return AttributeId
     */
    public function getId(): AttributeId
    {
        return $this->id;
    }
}
