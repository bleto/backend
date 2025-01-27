<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\DependencyInjection;

use Ergonode\Designer\Application\DependencyInjection\CompilerPass\TemplateGeneratorStrategyCompilerPass;
use Ergonode\Designer\Application\DependencyInjection\CompilerPass\TemplateRelationCheckerCompilerPass;
use Ergonode\Designer\Domain\Checker\TemplateRelationCheckerInterface;
use Ergonode\Designer\Infrastructure\Generator\TemplateGeneratorInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 */
class ErgonodeDesignerExtension extends Extension
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
            ->registerForAutoconfiguration(TemplateGeneratorInterface::class)
            ->addTag(TemplateGeneratorStrategyCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(TemplateRelationCheckerInterface::class)
            ->addTag(TemplateRelationCheckerCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
