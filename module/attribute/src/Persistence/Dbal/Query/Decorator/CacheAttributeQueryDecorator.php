<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Query\Decorator;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Attribute\Domain\View\AttributeViewModel;

/**
 */
class CacheAttributeQueryDecorator implements AttributeQueryInterface
{
    /**
     * @var AttributeQueryInterface
     */
    private $attributeQuery;

    /**
     * @var array
     */
    private $cache = [];

    /**
     * @param AttributeQueryInterface $attributeQuery
     */
    public function __construct(AttributeQueryInterface $attributeQuery)
    {
        $this->attributeQuery = $attributeQuery;
    }

    /**
     * @param AttributeCode $code
     *
     * @return bool
     */
    public function checkAttributeExistsByCode(AttributeCode $code): bool
    {
        return $this->attributeQuery->checkAttributeExistsByCode($code);
    }

    /**
     * @param AttributeCode $code
     *
     * @return AttributeViewModel
     */
    public function findAttributeByCode(AttributeCode $code): ?AttributeViewModel
    {
        $key = $code->getValue();
        if (!isset($this->cache[$key])) {
            $this->cache[$key] = $this->attributeQuery->findAttributeByCode($code);
        }

        return $this->cache[$key];
    }

    /**
     * @param AttributeId $id
     *
     * @return AttributeType|null
     */
    public function findAttributeType(AttributeId $id): ?AttributeType
    {
        $key = sprintf('type_%s', $id->getValue());
        if (!array_key_exists($key, $this->cache)) {
            $this->cache[$key] = $this->attributeQuery->findAttributeType($id);
        }

        return $this->cache[$key];
    }


    /**
     * @param AttributeId $attributeId
     *
     * @return array
     */
    public function getAttributeValueRange(AttributeId $attributeId): array
    {
        return $this->attributeQuery->getAttributeValueRange($attributeId);
    }

    /**
     * @param AttributeId $attributeId
     *
     * @return array|null
     */
    public function getAttribute(AttributeId $attributeId): ?array
    {
        $key = $attributeId->getValue();
        if (!isset($this->cache[$key])) {
            $this->cache[$key] = $this->attributeQuery->getAttribute($attributeId);
        }

        return $this->cache[$key];
    }

    /**
     * @return array
     */
    public function getAllAttributeCodes(): array
    {
        return $this->attributeQuery->getAllAttributeCodes();
    }

    /**
     * @param AttributeId $id
     * @param OptionKey   $key
     *
     * @return OptionInterface|null
     */
    public function findAttributeOption(AttributeId $id, OptionKey $key): ?OptionInterface
    {
        return $this->attributeQuery->findAttributeOption($id, $key);
    }
}
