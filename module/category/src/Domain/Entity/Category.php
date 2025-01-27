<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Domain\Entity;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\Event\ValueAddedEvent;
use Ergonode\Value\Domain\Event\ValueChangedEvent;
use Ergonode\Value\Domain\Event\ValueRemovedEvent;
use Ergonode\Category\Domain\Event\CategoryCreatedEvent;
use Ergonode\Category\Domain\Event\CategoryNameChangedEvent;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Webmozart\Assert\Assert;
use JMS\Serializer\Annotation as JMS;

/**
 */
class Category extends AbstractAggregateRoot
{
    /**
     * @var CategoryId
     *
     * @JMS\Type("Ergonode\Category\Domain\Entity\CategoryId")
     */
    private $id;

    /**
     * @var CategoryCode
     *
     * @JMS\Type("Ergonode\Category\Domain\ValueObject\CategoryCode")
     */
    private $code;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $name;

    /**
     * @var ValueInterface[]
     *
     * @JMS\Type("array<string, Ergonode\Value\Domain\ValueObject\AbstractValue>")
     */
    private $attributes;

    /**
     * @param CategoryId         $id
     * @param CategoryCode       $code
     * @param TranslatableString $name
     * @param ValueInterface[]   $attributes
     */
    public function __construct(CategoryId $id, CategoryCode $code, TranslatableString $name, array $attributes = [])
    {
        Assert::allIsInstanceOf($attributes, ValueInterface::class);

        $this->apply(new CategoryCreatedEvent($id, $code, $name, $attributes));
    }

    /**
     * @return CategoryId
     */
    public function getId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return CategoryCode
     */
    public function getCode(): CategoryCode
    {
        return $this->code;
    }

    /**
     * @return TranslatableString
     */
    public function getName(): TranslatableString
    {
        return $this->name;
    }

    /**
     * @param TranslatableString $title
     */
    public function changeTitle(TranslatableString $title): void
    {
        if ($this->name->getTranslations() !== $title->getTranslations()) {
            $this->apply(new CategoryNameChangedEvent($this->name, $title));
        }
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

        $this->apply(new ValueAddedEvent($attributeCode, $value));
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
            $this->apply(new ValueChangedEvent($attributeCode, $this->attributes[$attributeCode->getValue()], $value));
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

        $this->apply(new ValueRemovedEvent($attributeCode, $this->attributes[$attributeCode->getValue()]));
    }

    /**
     * @param CategoryCreatedEvent $event
     */
    protected function applyCategoryCreatedEvent(CategoryCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->code = $event->getCode();
        $this->name = $event->getName();
        $this->attributes = $event->getAttributes();
    }

    /**
     * @param CategoryNameChangedEvent $event
     */
    protected function applyCategoryNameChangedEvent(CategoryNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    /**
     * @param ValueAddedEvent $event
     */
    protected function applyValueAddedEvent(ValueAddedEvent $event): void
    {
        $this->attributes[$event->getAttributeCode()->getValue()] = $event->getValue();
    }

    /**
     * @param ValueChangedEvent $event
     */
    protected function applyValueChangedEvent(ValueChangedEvent $event): void
    {
        $this->attributes[$event->getAttributeCode()->getValue()] = $event->getTo();
    }

    /**
     * @param ValueRemovedEvent $event
     */
    protected function applyValueRemovedEvent(ValueRemovedEvent $event): void
    {
        unset($this->attributes[$event->getAttributeCode()->getValue()]);
    }
}
