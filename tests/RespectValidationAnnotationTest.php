<?php

namespace Andresmeireles\RespectAnnotation;

use Andresmeireles\RespectAnnotation\TestEntities\NotEntity;
use Andresmeireles\RespectAnnotation\TestEntities\OptionalEntity;
use Andresmeireles\RespectAnnotation\TestEntities\PrivateEntity;
use Andresmeireles\RespectAnnotation\TestEntities\PublicEntity;
use PHPUnit\Framework\TestCase;

class RespectValidationAnnotationTest extends TestCase
{
    public function testExecuteClassValidation()
    {
        $testEntity = new PublicEntity();
        $validator = new RespectValidationAnnotation();
        $testEntity->name = 'Andre';
        $testEntity->lastName = 'Meireles';
        $testEntity->age = 27;
        $result = $validator->executeClassValidation($testEntity);
        $this->assertEquals(null, $result);
    }

    public function testExecuteClassValidationError()
    {
        $testEntity = new PublicEntity();
        $validator = new RespectValidationAnnotation();
        $testEntity->name = 'Andre Meireles';
        $testEntity->lastName = 'Meireles';
        $testEntity->age = 27;
        $result = $validator->executeClassValidation($testEntity);
        $this->assertEquals([
            'name must not contain whitespace'
        ], $result);
    }

    public function testExecuteClassValidationManyError()
    {
        $testEntity = new PublicEntity();
        $validator = new RespectValidationAnnotation();
        $testEntity->name = 'Andre Meireles';
        $testEntity->lastName = 'Meireles';
        $testEntity->age = 'zeca';
        $result = $validator->executeClassValidation($testEntity);
        $this->assertEquals([
            'name must not contain whitespace',
            'age precisa ser um número.'
        ], $result);
    }

    public function testGetAllErrorMessages()
    {
        $testEntity = new PublicEntity();
        $validator = new RespectValidationAnnotation();
        $testEntity->name = 'Andre';
        $testEntity->lastName = 'Meireles';
        $testEntity->age = 27;
        $validator->executeClassValidation($testEntity);
        $result = $validator->getAllErrorMessages();
        $this->assertEquals(null, $result);
    }

    public function testGetAllErrorMessagesError()
    {
        $testEntity = new PublicEntity();
        $validator = new RespectValidationAnnotation();
        $testEntity->name = 'Andre Zeca';
        $testEntity->lastName = 'Meireles';
        $testEntity->age = 27;
        $validator->executeClassValidation($testEntity);
        $result = $validator->getAllErrorMessages();
        $this->assertEquals([
            [
                [
                    'name must not contain whitespace'
                ]
            ]
        ], $result);
    }

    public function testGetAllErrorMessagesWithManyErrors()
    {
        $testEntity = new PublicEntity();
        $validator = new RespectValidationAnnotation();
        $testEntity->name = 'Andre Zeca';
        $testEntity->lastName = 'Meireles';
        $testEntity->age = 'ss';
        $validator->executeClassValidation($testEntity);
        $result = $validator->getAllErrorMessages();
        $this->assertEquals([
            0 => [
                [
                    'name must not contain whitespace'
                ]
            ],
            2 => [
                [
                   'age precisa ser um número.'
                ]
            ]
        ], $result);
    }

    public function testGetAllErrorMessagesWithManyErrorsInParam()
    {
        $testEntity = new PublicEntity();
        $validator = new RespectValidationAnnotation();
        $testEntity->name = 'Andre Zeca 23';
        $testEntity->lastName = 'Meireles';
        $testEntity->age = 'ss';
        $validator->executeClassValidation($testEntity);
        $result = $validator->getAllErrorMessages();
        $this->assertEquals([
            0 => [
                [
                    'name must not contain whitespace'
                ],
                [
                    'name must contain only letters (a-z)'
                ]
            ],
            2 => [
                [
                    'age precisa ser um número.'
                ]
            ]
        ], $result);
    }

    public function testGetAllErrorMessagesWithManyErrorsInParamPrivate()
    {
        $testEntity = new PrivateEntity();
        $validator = new RespectValidationAnnotation();
        $testEntity->setName('Andre Zeca 23');
        $testEntity->setLastName('Meireles');
        $testEntity->setAge('ss');
        $validator->executeClassValidation($testEntity);
        $result = $validator->getAllErrorMessages();
        $this->assertEquals([
            0 => [
                [
                    'name must not contain whitespace'
                ],
                [
                    'name must contain only letters (a-z)'
                ]
            ],
            2 => [
                [
                    'age precisa ser um número.'
                ]
            ]
        ], $result);
    }

    public function testOptionalValidation(): void
    {
        $testEntity = new OptionalEntity();
        $validator = new RespectValidationAnnotation();
        $testEntity->setName('Andre Zeca 23');
        $validator->executeClassValidation($testEntity);
        $result = $validator->getAllErrorMessages();
        $this->assertEquals([
            0 => [
                [
                    'name must not contain whitespace'
                ]
            ]
        ], $result);
    }

    public function testOptionalValidationSuccess(): void
    {
        $testEntity = new OptionalEntity();
        $validator = new RespectValidationAnnotation();
        $testEntity->setName('');
        $validator->executeClassValidation($testEntity);
        $result = $validator->getAllErrorMessages();
        $this->assertNull($result);
    }

    public function testNotValidation(): void
    {
        $testEntity = new NotEntity();
        $validator = new RespectValidationAnnotation();
        $testEntity->name = 'AndreZeca23';
        $validator->executeClassValidation($testEntity);
        $result = $validator->getAllErrorMessages();
        $this->assertEquals([
            0 => [
                [
                    'name must not not contain whitespace'
                ]
            ]
        ], $result);
    }

    public function testNotValidationSuccess(): void
    {
        $testEntity = new NotEntity();
        $validator = new RespectValidationAnnotation();
        $testEntity->name = 'fred dabura';
        $validator->executeClassValidation($testEntity);
        $result = $validator->getAllErrorMessages();
        $this->assertNull($result);
    }
}
