<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
interface ColumnInterface
{
    /**
     * @return string
     */
    public function getField(): string;

    /**
     * @return string|null
     */
    public function getLabel(): ?string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return null|int
     */
    public function getWidth(): ?int;

    /**
     * @return bool
     */
    public function isVisible(): bool;

    /**
     * @return bool
     */
    public function isEditable(): bool;

    /**
     * @return Language
     */
    public function getLanguage(): ?Language;

    /**
     * @param Language $language
     */
    public function setLanguage(Language $language): void;

    /**
     * @param string $id
     * @param array  $row
     *
     * @return mixed
     */
    public function render(string $id, array $row);

    /**
     * @return FilterInterface|null
     */
    public function getFilter(): ?FilterInterface;

    /**
     * @param string       $key
     * @param string|array $value
     */
    public function setExtension(string $key, $value): void;

    /**
     * @return array
     */
    public function getExtensions(): array;
}
