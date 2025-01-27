<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Attribute\Tests\Infrastructure\Validator;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Validator\AttributeExists;
use Ergonode\Attribute\Infrastructure\Validator\AttributeExistsValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class AttributeExistsValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var AttributeRepositoryInterface|MockObject
     */
    private $repository;

    /**
     */
    protected function setUp()
    {
        $this->repository = $this->createMock(AttributeRepositoryInterface::class);
        parent::setUp();
    }


    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testWrongValueProvided(): void
    {
        $this->validator->validate(new \stdClass(), new AttributeExists());
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testWrongConstraintProvided(): void
    {
        /** @var Constraint $constrain */
        $constrain = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constrain);
    }

    /**
     */
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new AttributeExists());

        $this->assertNoViolation();
    }

    /**
     */
    public function testAttributeExistsValidation(): void
    {
        $this->repository->method('load')->willReturn($this->createMock(AbstractAttribute::class));
        $this->validator->validate(AttributeId::generate(), new AttributeExists());

        $this->assertNoViolation();
    }

    /**
     */
    public function testAttributeNotExistsValidation(): void
    {
        $this->repository->method('load')->willReturn(null);
        $constraint = new AttributeExists();
        $value = AttributeId::generate();
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     * @return AttributeExistsValidator
     */
    protected function createValidator(): AttributeExistsValidator
    {
        return new AttributeExistsValidator($this->repository);
    }
}
