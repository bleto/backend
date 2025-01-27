<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Entity;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\Product\Domain\Event\ProductAddedToCategory;
use Ergonode\Product\Domain\Event\ProductCreated;
use Ergonode\Product\Domain\Event\ProductRemovedFromCategory;
use Ergonode\Product\Domain\Event\ProductStatusChanged;
use Ergonode\Product\Domain\Event\ProductValueAdded;
use Ergonode\Product\Domain\Event\ProductValueChanged;
use Ergonode\Product\Domain\Event\ProductValueRemoved;
use Ergonode\Product\Domain\Event\ProductVersionIncreased;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Product\Domain\ValueObject\ProductStatus;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

/**
 */
abstract class AbstractProduct extends AbstractAggregateRoot
{
    private const STATUS = 'esa_status';

    /**
     * @var ProductId
     */
    private $id;

    /**
     * @var Sku
     */
    private $sku;

    /**
     * @var TemplateId
     */
    private $designTemplateId;

    /**
     * @var integer
     */
    private $version;

    /**
     * @var ValueInterface[]
     */
    private $attributes;

    /**
     * @var string[]
     */
    private $categories;

    /**
     * @param ProductId  $id
     * @param Sku        $sku
     * @param TemplateId $templateId
     * @param array      $categories
     * @param array      $attributes
     */
    public function __construct(ProductId $id, Sku $sku, TemplateId $templateId, array $categories = [], array $attributes = [])
    {
        $attributes = array_filter(
            $attributes,
            function ($value) {
                return $value !== null;
            }
        );

        $this->apply(new ProductCreated($id, $sku, $templateId, $categories, $attributes));
    }

    /**
     * @return AbstractId|ProductId
     */
    public function getId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return Sku
     */
    public function getSku(): Sku
    {
        return $this->sku;
    }

    /**
     * @return ProductStatus
     */
    public function getStatus(): ProductStatus
    {
        return new ProductStatus($this->getAttribute(new AttributeCode(self::STATUS))->getValue());
    }

    /**
     */
    public function accept(): void
    {
        $this->apply(new ProductStatusChanged($this->getStatus(), new ProductStatus(ProductStatus::STATUS_ACCEPTED)));
    }

    /**
     * @param ProductDraft $draft
     */
    public function applyDraft(ProductDraft $draft): void
    {
        $attributes = $draft->getAttributes();
        foreach ($attributes as $code => $value) {
            $attributeCode = new AttributeCode((string) $code);
            if ($this->hasAttribute($attributeCode)) {
                $this->changeAttribute($attributeCode, $value);
            } else {
                $this->addAttribute($attributeCode, $value);
            }
        }

        foreach ($this->getAttributes() as $code => $attributes) {
            $attributeCode = new AttributeCode((string) $code);
            if (!$draft->hasAttribute($attributeCode)) {
                $this->removeAttribute($attributeCode);
            }
        }
    }

    /**
     * @param CategoryCode $categoryCode
     *
     * @return bool
     */
    public function belongToCategory(CategoryCode $categoryCode): bool
    {
        return isset($this->categories[$categoryCode->getValue()]);
    }

    /**
     * @param CategoryCode $categoryCode
     */
    public function addToCategory(CategoryCode $categoryCode): void
    {
        if (!$this->belongToCategory($categoryCode)) {
            $this->apply(new ProductAddedToCategory($categoryCode));
        }
    }

    /**
     * @param CategoryCode $categoryCode
     */
    public function removeFromCategory(CategoryCode $categoryCode): void
    {
        if ($this->belongToCategory($categoryCode)) {
            $this->apply(new ProductRemovedFromCategory($categoryCode));
        }
    }

    /**
     * @return CategoryCode[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param AttributeCode $attributeCode
     *
     * @return bool
     */
    public function hasAttribute(AttributeCode $attributeCode): bool
    {
        return isset($this->attributes[$attributeCode->getValue()]);
    }

    /**
     * @param AttributeCode $attributeCode
     *
     * @return ValueInterface
     */
    public function getAttribute(AttributeCode $attributeCode): ValueInterface
    {
        if (!$this->hasAttribute($attributeCode)) {
            throw new \RuntimeException(sprintf('Value for attribute %s not exists', $attributeCode->getValue()));
        }

        return clone $this->attributes[$attributeCode->getValue()];
    }

    /**
     * @param AttributeCode  $attributeCode
     * @param ValueInterface $value
     */
    public function addAttribute(AttributeCode $attributeCode, ValueInterface $value): void
    {
        if ($this->hasAttribute($attributeCode)) {
            throw new \RuntimeException('Value already exists');
        }

        $this->apply(new ProductValueAdded($attributeCode, $value));
    }

    /**
     * @return ValueInterface[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param AttributeCode  $attributeCode
     * @param ValueInterface $value
     */
    public function changeAttribute(AttributeCode $attributeCode, ValueInterface $value): void
    {
        if (!$this->hasAttribute($attributeCode)) {
            throw new \RuntimeException('Value note exists');
        }

        if ((string) $this->attributes[$attributeCode->getValue()] !== (string) $value) {
            $this->apply(new ProductValueChanged($attributeCode, $this->attributes[$attributeCode->getValue()], $value));
        }
    }

    /**
     * @param AttributeCode $attributeCode
     */
    public function removeAttribute(AttributeCode $attributeCode): void
    {
        if (!$this->hasAttribute($attributeCode)) {
            throw new \RuntimeException('Value note exists');
        }

        $this->apply(new ProductValueRemoved($attributeCode, $this->attributes[$attributeCode->getValue()]));
    }

    /**
     * @param ProductCreated $event
     */
    protected function applyProductCreated(ProductCreated $event): void
    {
        $this->id = $event->getId();
        $this->sku = $event->getSku();
        $this->attributes = [];
        $this->categories = [];
        $this->designTemplateId = $event->getTemplateId();
        $this->version = 1;
        foreach ($event->getCategories() as $category) {
            $this->categories[$category->getValue()] = $category;
        }
        foreach ($event->getAttributes() as $key => $attribute) {
            $this->attributes[$key] = $attribute;
        }
    }

    /**
     * @param ProductAddedToCategory $event
     */
    protected function applyProductAddedToCategory(ProductAddedToCategory $event): void
    {
        $this->categories[$event->getCategoryCode()->getValue()] = $event->getCategoryCode();
    }

    /**
     * @param ProductRemovedFromCategory $event
     */
    protected function applyProductRemovedFromCategory(ProductRemovedFromCategory $event): void
    {
        unset($this->categories[$event->getCategoryCode()->getValue()]);
    }

    /**
     * @param ProductValueAdded $event
     */
    protected function applyProductValueAdded(ProductValueAdded $event): void
    {
        $this->attributes[$event->getAttributeCode()->getValue()] = $event->getValue();
    }

    /**
     * @param ProductValueChanged $event
     */
    protected function applyProductValueChanged(ProductValueChanged $event): void
    {
        $this->attributes[$event->getAttributeCode()->getValue()] = $event->getTo();
    }

    /**
     * @param ProductValueRemoved $event
     */
    protected function applyProductValueRemoved(ProductValueRemoved $event): void
    {
        unset($this->attributes[$event->getAttributeCode()->getValue()]);
    }

    /**
     * @param ProductVersionIncreased $event
     */
    protected function applyProductVersionIncreased(ProductVersionIncreased $event): void
    {
        $this->version = $event->getTo();
    }

    /**
     * @param ProductStatusChanged $event
     */
    protected function applyProductStatusChanged(ProductStatusChanged $event): void
    {
        $this->attributes[self::STATUS] = new StringValue($event->getTo()->getValue());
    }
}
