<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Command;

use Ergonode\Transformer\Domain\Entity\ProcessorId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class StopProcessImportLineCommand
{
    /**
     * @var ProcessorId
     *
     * @JMS\Type("Ergonode\Transformer\Domain\Entity\ProcessorId")
     */
    private $id;

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     */
    private $reason;

    /**
     * @param ProcessorId $id
     * @param null|string $reason
     */
    public function __construct(ProcessorId $id, ?string $reason = null)
    {
        $this->id = $id;
        $this->reason = $reason;
    }

    /**
     * @return ProcessorId
     */
    public function getId(): ProcessorId
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }
}
