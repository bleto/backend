<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Designer\Tests\Domain\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Designer\Domain\Command\UpdateTemplateCommand;
use Ergonode\Designer\Domain\Entity\TemplateId;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateDesignerTemplateCommandTest extends TestCase
{
    /**
     */
    public function testResultValues(): void
    {
        /** @var TemplateId $id */
        $id = $this->createMock(TemplateId::class);
        $name = 'Any Name';
        $elements = new ArrayCollection();
        $sections = new ArrayCollection();

        $command = new UpdateTemplateCommand($id, $name, $elements, $sections);
        $this->assertSame($id, $command->getId());
        $this->assertSame($name, $command->getName());
        $this->assertSame($elements, $command->getElements());
        $this->assertSame($sections, $command->getSections());
    }
}
