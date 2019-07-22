<?php

namespace Andresmeireles\RespectAnnotation;

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
}
