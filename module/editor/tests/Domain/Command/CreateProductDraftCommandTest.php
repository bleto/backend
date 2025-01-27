<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Editor\Tests\Domain\Command;

use Ergonode\Editor\Domain\Command\CreateProductDraftCommand;
use Ergonode\Product\Domain\Entity\ProductId;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateProductDraftCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testGetters(): void
    {
        $productId = $this->createMock(ProductId::class);

        $command = new CreateProductDraftCommand($productId);
        $this->assertEquals($productId, $command->getProductId());
    }
}
