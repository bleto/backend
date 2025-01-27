<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Domain\Entity;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Editor\Domain\Event\ProductDraftApplied;
use Ergonode\Editor\Domain\Event\ProductDraftCreated;
use Ergonode\Editor\Domain\Event\ProductDraftValueAdded;
use Ergonode\Editor\Domain\Event\ProductDraftValueChanged;
use Ergonode\Editor\Domain\Event\ProductDraftValueRemoved;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

/**
 */
class ProductDraft extends AbstractAggregateRoot
{
    /**
     * @var ProductDraftId
     */
    private $id;

    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var bool
     */
    private $applying;

    /**
     * @var ValueInterface[]
     */
    private $attributes;

    /**
     * @param ProductDraftId  $id
     * @param AbstractProduct $product
     */
    public function __construct(ProductDraftId $id, AbstractProduct $product)
    {
        $this->apply(new ProductDraftCreated($id, $product->getId()));

        foreach ($product->getAttributes() as $attributeCode => $value) {
            $this->apply(new ProductDraftValueAdded(new AttributeCode((string) $attributeCode), $value));
        }
    }

    /**
     * @return AbstractId|ProductDraftId
     */
    public function getId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    /**
     * @return bool
     */
    public function isApplying(): bool
    {
        return $this->applying;
    }

    /**
     */
    public function applied(): void
    {
        $this->apply(new ProductDraftApplied());
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
            throw new \RuntimeException('Value not exists');
        }

        return clone $this->attributes[$attributeCode->getValue()];
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
     * @param ValueInterface $valueInterface
     */
    public function addAttribute(AttributeCode $attributeCode, ValueInterface $valueInterface): void
    {
        if ($this->hasAttribute($attributeCode)) {
            throw new \RuntimeException('Value already exists');
        }

        $this->apply(new ProductDraftValueAdded($attributeCode, $valueInterface));
    }

    /**
     * @param AttributeCode  $attributeCode
     * @param ValueInterface $new
     */
    public function changeAttribute(AttributeCode $attributeCode, ValueInterface $new): void
    {
        if (!$this->hasAttribute($attributeCode)) {
            throw new \RuntimeException('Value note exists');
        }

        if ((string) $this->attributes[$attributeCode->getValue()] !== (string) $new) {
            $this->apply(new ProductDraftValueChanged($attributeCode, $this->attributes[$attributeCode->getValue()], $new));
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

        $this->apply(new ProductDraftValueRemoved($attributeCode, $this->attributes[$attributeCode->getValue()]));
    }

    /**
     * @param ProductDraftCreated $event
     */
    protected function applyProductDraftCreated(ProductDraftCreated $event): void
    {
        $this->id = $event->getId();
        $this->productId = $event->getProductId();
        $this->attributes = [];
        $this->applying = false;
    }

    /**
     * @param ProductDraftValueAdded $event
     */
    protected function applyProductDraftValueAdded(ProductDraftValueAdded $event): void
    {
        $this->attributes[$event->getAttributeCode()->getValue()] = $event->getTo();
    }

    /**
     * @param ProductDraftValueChanged $event
     */
    protected function applyProductDraftValueChanged(ProductDraftValueChanged $event): void
    {
        $this->attributes[$event->getAttributeCode()->getValue()] = $event->getTo();
    }

    /**
     * @param ProductDraftValueRemoved $event
     */
    protected function applyProductDraftValueRemoved(ProductDraftValueRemoved $event): void
    {
        unset($this->attributes[$event->getAttributeCode()->getValue()]);
    }

    /**
     * @param ProductDraftApplied $event
     */
    protected function applyProductDraftApplied(ProductDraftApplied $event): void
    {
        $this->applying = true;
    }
}
