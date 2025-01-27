<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\View\Factory;

use Ergonode\Attribute\Domain\Entity\AttributeGroupId;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\View\AttributeViewModel;

/**
 */
class AttributeViewModelFactory
{
    /**
     * @param array $record
     *
     * @return AttributeViewModel
     */
    public function create(array $record): AttributeViewModel
    {
        $groups = [];
        foreach ($record['groups'] as $id) {
            $groups[] = new AttributeGroupId($id);
        }

        return new AttributeViewModel(
            new AttributeId($record['id']),
            new AttributeCode($record['code']),
            $record['type'],
            $groups
        );
    }
}
