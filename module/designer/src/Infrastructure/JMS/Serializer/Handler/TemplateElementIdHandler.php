<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Designer\Domain\Entity\TemplateElementId;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

/**
 */
class TemplateElementIdHandler implements SubscribingHandlerInterface
{
    /**
     * @return array
     */
    public static function getSubscribingMethods(): array
    {
        $methods = [];
        $formats = ['json', 'xml', 'yml'];

        foreach ($formats as $format) {
            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'type' => TemplateElementId::class,
                'format' => $format,
                'method' => 'serialize',
            ];

            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'type' => TemplateElementId::class,
                'format' => $format,
                'method' => 'deserialize',
            ];
        }

        return $methods;
    }

    /**
     * @param SerializationVisitorInterface $visitor
     * @param TemplateElementId             $templateElementId
     * @param array                         $type
     * @param Context                       $context
     *
     * @return string
     */
    public function serialize(SerializationVisitorInterface $visitor, TemplateElementId $templateElementId, array $type, Context $context): string
    {
        return $templateElementId->getValue();
    }

    /**
     * @param DeserializationVisitorInterface $visitor
     * @param mixed                           $data
     * @param array                           $type
     * @param Context                         $context
     *
     * @return TemplateElementId
     */
    public function deserialize(DeserializationVisitorInterface $visitor, $data, array $type, Context $context): TemplateElementId
    {
        return new TemplateElementId($data);
    }
}
