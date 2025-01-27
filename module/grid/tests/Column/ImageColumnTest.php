<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Grid\Tests\Column;

use Ergonode\Grid\Column\ImageColumn;
use PHPUnit\Framework\TestCase;

/**
 */
class ImageColumnTest extends TestCase
{
    /**
     */
    public function testGetters(): void
    {
        $field = 'Any id';
        $url = 'Any url';

        $column = new ImageColumn($field, $url);
        $this->assertSame($field, $column->getField());
        $this->assertNull($column->getLabel());
        $this->assertSame(ImageColumn::TYPE, $column->getType());
    }

    /**
     */
    public function testRender(): void
    {
        $field = 'Any id';
        $label = 'Any label';
        $record = [$field => 'file.txt'];

        $column = new ImageColumn($field, $label);
        $result = $column->render($field, $record);
        $this->assertSame($label.'/'.$record[$field], $result);
    }
}
