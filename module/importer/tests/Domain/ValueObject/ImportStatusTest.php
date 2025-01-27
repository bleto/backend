<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Tests\Domain\ValueObject;

use Ergonode\Importer\Domain\ValueObject\ImportStatus;
use PHPUnit\Framework\TestCase;

/**
 * Class ImportStatusTest
 */
class ImportStatusTest extends TestCase
{
    /**
     * @param string $status
     *
     * @dataProvider importStatusProvider
     */
    public function testCreateWitchCorrectStatus(string $status): void
    {
        $status = new ImportStatus($status);

        $this->assertEquals($status, (string) $status);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWitchIncorrectStatus(): void
    {
         new ImportStatus('any incorrect status');
    }

    /**
     * @return \Generator
     */
    public static function importStatusProvider(): \Generator
    {
        foreach (ImportStatus::AVAILABLE as $status) {
            yield [
                $status,
            ];
        }
    }
}
