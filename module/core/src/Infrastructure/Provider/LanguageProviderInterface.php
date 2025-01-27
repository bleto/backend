<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Provider;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
interface LanguageProviderInterface
{
    /**
     * @param Language $language
     *
     * @return array
     */
    public function getLanguages(Language $language): array;

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getSystemLanguages(Language $language): array;
}
