<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Command;

use Ergonode\Transformer\Domain\Entity\TransformerId;
use JMS\Serializer\Annotation as JMS;

/**
 * Class CreateTransformerCommand
 */
class CreateTransformerCommand
{
    /**
     * @var TransformerId
     *
     * @JMS\Type("Ergonode\Transformer\Domain\Entity\TransformerId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $key;

    /**
     * @param string $name
     * @param string $key
     *
     * @throws \Exception
     */
    public function __construct(string $name, string $key)
    {
        $this->id = TransformerId::generate();
        $this->name = $name;
        $this->key = $key;
    }

    /**
     * @return TransformerId
     */
    public function getId(): TransformerId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}
