<?php

namespace Andresmeireles\RespectAnnotation\Annotation;

use PHPUnit\Framework\TestCase;

class RuleValidatorTest extends TestCase
{

    public function testExecuteNotValidationInParameter()
    {
        $rules = ['notEmpty'];
        $validator = new RuleValidator($rules);
        $validator->executeNotValidationInParameter([0 => '']);
        $this->assertNull($validator->getValidationErrors());
    }

    public function testExecuteNotValidationInParameterError()
    {
        $rules = ['notEmpty'];
        $validator = new RuleValidator($rules);
        $validator->executeNotValidationInParameter([0 => 'joao']);
        $result = $validator->getValidationErrors();
        $this->assertEquals(['"joao" não pode ser vazio.'], $result);
    }

    public function testGetAllValidationErrors()
    {
        $rules = ['notEmpty'];
        $validator = new RuleValidator($rules);
        $validator->executeDefaultValidationInParameter([0 => 'joao']);
        $result = $validator->getAllValidationErrors();
        $this->assertNull($result);
    }

    public function testGetAllValidationErrorsPositive()
    {
        $rules = ['notEmpty'];
        $validator = new RuleValidator($rules);
        $validator->executeDefaultValidationInParameter([0 => '']);
        $result = $validator->getAllValidationErrors();
        $this->assertIsArray($result);
    }

    public function testGetAllValidationErrorsPositiveMessage()
    {
        $rules = ['notEmpty'];
        $validator = new RuleValidator($rules);
        $validator->executeDefaultValidationInParameter([0 => '']);
        $result = $validator->getAllValidationErrors();
        $this->assertEquals([['"" não pode ser vazio.']], $result);
    }

    public function testGetValidationErrors()
    {
        $rules = ['notEmpty'];
        $validator = new RuleValidator($rules);
        $validator->executeDefaultValidationInParameter([0 => 'joao']);
        $result = $validator->getValidationErrors();
        $this->assertNull($result);
    }

    public function testGetValidationErrorsPositive()
    {
        $rules = ['notEmpty'];
        $validator = new RuleValidator($rules);
        $validator->executeDefaultValidationInParameter([0 => '']);
        $result = $validator->getValidationErrors();
        $this->assertIsArray($result);
    }

    public function testGetValidationErrorsPositiveMessage()
    {
        $rules = ['notEmpty'];
        $validator = new RuleValidator($rules);
        $validator->executeDefaultValidationInParameter([0 => '']);
        $result = $validator->getValidationErrors();
        $this->assertEquals(['"" não pode ser vazio.'], $result);
    }
//
//    public function testExecuteOptionalValidationInParameter()
//    {
//
//    }
//
//    public function testExecuteDefaultValidationInParameter()
//    {
//
//    }

    public function testExecuteValidationInParameter(): void
    {
        $rules = ['notEmpty'];
        $validator = new RuleValidator($rules);
        $this->assertNull($validator->executeDefaultValidationInParameter([0 => 'joao']));
    }

    public function testExecuteValidationWithManyRules(): void
    {
        $rules = ['notEmpty', 'notBlank', 'alpha'];
        $validator = new RuleValidator($rules);
        $this->assertNull($validator->executeDefaultValidationInParameter([0 => 'joao']));
    }

    public function testExecuteValidationWithRulesWithParam(): void
    {
        $rules = ['notEmpty', 'notBlank', 'alpha', 'length(3)'];
        $validator = new RuleValidator($rules);
        $this->assertNull($validator->executeDefaultValidationInParameter([0 => 'joao']));
    }

    public function testGetValidationErrorsNullResult()
    {
        $rules = ['notEmpty', 'notBlank', 'alpha', 'length(2)'];
        $validator = new RuleValidator($rules);
        $validator->executeDefaultValidationInParameter([0 => 'joao']);
        $result = $validator->getValidationErrors();
        $this->assertEquals(null, $result);
    }

    public function testGetValidationErrorsSingleError()
    {
        $rules = ['notEmpty', 'notBlank', 'alpha', 'length(2)', 'noWhitespace'];
        $validator = new RuleValidator($rules);
        $validator->executeDefaultValidationInParameter([0 => 'joao do ceu']);
        $result = $validator->getValidationErrors();
        $this->assertEquals(['"joao do ceu" must not contain whitespace'], $result);
    }

    public function testGetValidationErrorsMultiErrors()
    {
        $rules = ['notEmpty', 'notBlank', 'alpha', 'length(50)', 'noWhitespace'];
        $validator = new RuleValidator($rules);
        $validator->executeDefaultValidationInParameter([0 => 'joao do ceu']);
        $result = $validator->getAllValidationErrors();
        $this->assertEquals([
            [
                '"joao do ceu" must have a length greater than "50"'
            ],
            [
                '"joao do ceu" must not contain whitespace'
            ]
        ], $result);
    }
}
