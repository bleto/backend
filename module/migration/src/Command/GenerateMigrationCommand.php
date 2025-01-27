<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration\Command;

use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand;
use Ergonode\Migration\Provider\MigrationConfigurationProvider;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 */
class GenerateMigrationCommand extends AbstractCommand
{
    private const FILENAME_TEMPLATE = '%s/VersionModule%s.php';

    /**
     * @param MigrationConfigurationProvider $configurationService
     */
    public function __construct(MigrationConfigurationProvider $configurationService)
    {
        $this->setMigrationConfiguration($configurationService->configure());

        parent::__construct();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return null|int|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $configuration = $this->getMigrationConfiguration($input, $output);

        $template = \file_get_contents(__DIR__.'/../Resources/migration.tpl');

        $version = $configuration->generateVersionNumber();
        $path = $this->generateMigration($configuration, $version, $template);

        $output->writeln(\sprintf('Generated migration class:"<info>%s</info>"', $path));
    }

    /**
     * @param Configuration $configuration
     * @param string        $version
     * @param string        $template
     *
     * @return string
     */
    protected function generateMigration(Configuration $configuration, string $version, string $template): string
    {
        $migration = $this->replace($template, $version, $configuration->getMigrationsNamespace());

        $dir = $configuration->getMigrationsDirectory();
        $path = \sprintf(self::FILENAME_TEMPLATE, $dir, $version);

        \file_put_contents($path, $migration);

        return $path;
    }

    /**
     *
     */
    protected function configure(): void
    {
        parent::configure();

        $this->setName('ergonode:migrations:generate');
    }

    /**
     * @param string $template
     * @param string $version
     * @param string $namespace
     *
     * @return string
     */
    private function replace(string $template, string $version, string $namespace): string
    {
        $placeholders = $this->getPlaceholders($version, $namespace);

        return \str_replace(
            \array_keys($placeholders),
            \array_values($placeholders),
            $template
        );
    }

    /**
     * @param string $version
     * @param string $namespace
     *
     * @return array
     */
    private function getPlaceholders(string $version, string $namespace): array
    {
        return [
            '%namespace%' => $namespace,
            '%version%' => $version,
        ];
    }
}
