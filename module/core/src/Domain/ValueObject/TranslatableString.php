<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\ValueObject;

/**
 * Class TranslatableString
 */
class TranslatableString implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $translations;

    /**
     * @param array $translations
     */
    public function __construct(array $translations = [])
    {
        $this->translations = [];
        foreach ($translations as $language => $translation) {
            $this->translations = $this->merge($this->translations, new Language($language), $translation);
        }
    }

    /**
     * @param Language $language
     * @param string   $value
     *
     * @return TranslatableString
     */
    public static function create(Language $language, string $value): self
    {
        return new self([$language->getCode() => $value]);
    }

    /**
     * @param Language $language
     * @param string   $value
     *
     * @return TranslatableString
     */
    public function add(Language $language, string $value): self
    {
        return new self($this->merge($this->translations, $language, $value));
    }

    /**
     * @param Language $language
     * @param string   $value
     *
     * @return TranslatableString
     */
    public function change(Language $language, string $value): self
    {
        return new self($this->merge($this->translations, $language, $value));
    }

    /**
     * @param Language $language
     *
     * @return bool
     */
    public function has(Language $language): bool
    {
        return isset($this->translations[$language->getCode()]);
    }

    /**
     * @param Language $language
     *
     * @return TranslatableString
     */
    public function remove(Language $language): self
    {
        $new = $this->translations;
        unset($new[$language->getCode()]);

        return new self($new);
    }

    /**
     * @param Language $language
     *
     * @return null|string
     */
    public function get(Language $language): ?string
    {
        return $this->translations[$language->getCode()] ?? null;
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->translations);
    }


    /**
     * @return string[]
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * @param array    $array
     * @param Language $language
     * @param string   $value
     *
     * @return array
     */
    private function merge(array $array, Language $language, string $value): array
    {
        return array_merge($array, [$language->getCode() => $value]);
    }
}
