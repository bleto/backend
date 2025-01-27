<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Form\DataTransformer;

use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 */
class LanguageDataTransformer implements DataTransformerInterface
{
    /**
     * @param Language|null $value
     *
     * @return null|string
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof Language) {
                return $value->getCode();
            }
            throw new TransformationFailedException('Invalid Language object');
        }

        return null;
    }

    /**
     * @param string|null $value
     *
     * @return Language|null
     */
    public function reverseTransform($value): ?Language
    {
        if ($value) {
            try {
                return new Language($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid Language "%s" value', $value));
            }
        }

        return null;
    }
}
