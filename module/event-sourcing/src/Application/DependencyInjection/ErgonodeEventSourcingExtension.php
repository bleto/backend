<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Application\DependencyInjection;

use Ergonode\EventSourcing\Application\DependencyInjection\CompilerPass\DomainEventProjectorCompilerPass;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 */
class ErgonodeEventSourcingExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../Resources/config')
        );

        $container
            ->registerForAutoconfiguration(DomainEventProjectorInterface::class)
            ->addTag(DomainEventProjectorCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
