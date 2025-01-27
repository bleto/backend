<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateRemovedEvent implements DomainEventInterface
{
    /**
     * @var string|null
     *
     * @JMS\Type("string")
     */
    private $reason;

    /**
     * @param string|null $reason
     */
    public function __construct(?string $reason = null)
    {
        $this->reason = $reason;
    }

    /**
     * @return string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }
}
