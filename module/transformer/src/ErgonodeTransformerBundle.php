<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer;

use Ergonode\Core\Application\AbstractModule;
use Ergonode\Transformer\Application\DependencyInjection\CompilerPass\TransformerActionCompilerPass;
use Ergonode\Transformer\Application\DependencyInjection\CompilerPass\TransformerGeneratorStrategyCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ErgonodeTransformerBundle
 */
class ErgonodeTransformerBundle extends AbstractModule
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new TransformerGeneratorStrategyCompilerPass());
        $container->addCompilerPass(new TransformerActionCompilerPass());
    }
}
