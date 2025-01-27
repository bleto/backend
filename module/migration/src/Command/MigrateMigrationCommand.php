<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration\Command;

use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Ergonode\Migration\Provider\MigrationConfigurationProvider;

/**
 */
class MigrateMigrationCommand extends MigrateCommand
{
     /**
     * @param MigrationConfigurationProvider $configurationService
     */
    public function __construct(MigrationConfigurationProvider $configurationService)
    {
        $this->setMigrationConfiguration($configurationService->configure());
        parent::__construct();
    }

    /**
     *
     */
    protected function configure(): void
    {
        parent::configure();

        $this->setName('ergonode:migrations:migrate');
    }
}
