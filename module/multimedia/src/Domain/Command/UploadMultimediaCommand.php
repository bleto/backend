<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Command;

use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 */
class UploadMultimediaCommand
{
    /**
     * @var MultimediaId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @param string       $name
     * @param UploadedFile $file
     *
     * @throws \Exception
     */
    public function __construct(string $name, UploadedFile $file)
    {
        $this->id = MultimediaId::generate();
        $this->name = $name;
        $this->file = $file;
    }

    /**
     * @return MultimediaId
     */
    public function getId(): MultimediaId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return UploadedFile
     */
    public function getFile(): UploadedFile
    {
        return $this->file;
    }
}
