<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Editor\Tests\Domain\Command;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Editor\Domain\Command\ChangeProductAttributeValueCommand;
use Ergonode\Editor\Domain\Entity\ProductDraftId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ChangeProductAttributeValueCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testGetters(): void
    {
        /** @var ProductDraftId|MockObject $draftId */
        $draftId = $this->createMock(ProductDraftId::class);
        /** @var AttributeId|MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);
        /** @var Language|MockObject $language */
        $language = $this->createMock(Language::class);
        $value = 'Any value';

        $command = new ChangeProductAttributeValueCommand($draftId, $attributeId, $language, $value);
        $this->assertSame($draftId, $command->getId());
        $this->assertSame($attributeId, $command->getAttributeId());
        $this->assertSame($language, $command->getLanguage());
        $this->assertSame($value, $value);
    }
}
