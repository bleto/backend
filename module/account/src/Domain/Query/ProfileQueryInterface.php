<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Query;

use Ergonode\Account\Domain\Entity\UserId;

/**
 */
interface ProfileQueryInterface
{
    /**
     * @param UserId $userId
     *
     * @return array
     */
    public function getProfile(UserId $userId): array;
}
