<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Reader\Tests\Infrastructure\Reader;

use Ergonode\Reader\Infrastructure\Processor\CsvReaderProcessor;
use PHPUnit\Framework\TestCase;

/**
 */
class CsvFileReaderTest extends TestCase
{
    private const FILE_NAME = 'test.csv';

    /**
     */
    public function testFileRead(): void
    {
        $file = \sprintf('%s/../../%s', __DIR__, self::FILE_NAME);

        $reader = new CsvReaderProcessor();
        $reader->open($file);

        $result = null;

        foreach ($reader as $line) {
            $result = $line;
        }

        $this->assertCount(2, $result);
        $this->arrayHasKey('id', $result);
        $this->arrayHasKey('value', $result);
        $this->assertNotEmpty($result);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testIncorrectFileRead(): void
    {
        $file = 'unknown file';

        $reader = new CsvReaderProcessor();
        $reader->open($file);
    }
}
