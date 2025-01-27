<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Form\Model;

use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class CreateUserFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="User first name is required")
     * @Assert\Length(min="1", max="128")
     */
    public $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="User last name is required")
     * @Assert\Length(min="3", max="128")
     */
    public $lastName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="User emailname is required")
     * @Assert\Email(mode="strict")
     */
    public $email;

    /**
     * @var Language
     *
     * @Assert\NotBlank(message="User language is required")
     */
    public $language;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="User password is required")
     * @Assert\Length(
     *     min="6",
     *     max="32",
     *     minMessage="User password is too short, should have at least {{ limit }} characters",
     *     maxMessage="User password is too long, should have at most {{ limit }} characters"
     * )
     */
    public $password;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="User password repeat is required")
     * @Assert\IdenticalTo(propertyPath="password", message="This value should be same as password")
     */
    public $passwordRepeat;
}
