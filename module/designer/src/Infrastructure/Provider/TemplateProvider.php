<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Provider;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\Designer\Infrastructure\Generator\TemplateGeneratorInterface;

/**
 */
class TemplateProvider
{
    /**
     * @var TemplateRepositoryInterface
     */
    private $repository;

    /**
     * @var TemplateGroupQueryInterface
     */
    private $query;

    /**
     * @var TemplateGeneratorProvider
     */
    private $provider;

    /**
     * @param TemplateRepositoryInterface $repository
     * @param TemplateGroupQueryInterface $query
     * @param TemplateGeneratorProvider   $provider
     */
    public function __construct(
        TemplateRepositoryInterface $repository,
        TemplateGroupQueryInterface $query,
        TemplateGeneratorProvider $provider
    ) {
        $this->repository = $repository;
        $this->query = $query;
        $this->provider = $provider;
    }


    /**
     * @param string $code
     *
     * @return Template
     * @throws \Exception
     */
    public function provide(string $code = TemplateGeneratorInterface::DEFAULT): Template
    {
        $id = TemplateId::fromKey($code);
        $template = $this->repository->load($id);
        if (!$template) {
            $groupId = $this->query->getDefaultId();
            $template = $this->provider->provide($code)->getTemplate($id, $groupId);
            $this->repository->save($template);
        }

        return $template;
    }
}
