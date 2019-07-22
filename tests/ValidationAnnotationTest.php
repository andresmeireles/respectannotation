<?php declare(strict_types = 1);

namespace Andresmeireles\RespectAnnotation;

use PHPUnit\Framework\TestCase;

class ValidationAnnotationTest extends TestCase
{
    public function testExecuteValidationInParameter(): void
    {
        $rules = ['value' => ['notEmpty']];
        $validator = new ValidationAnnotation($rules);
        $this->assertNull($validator->executeValidationInParameter([0 => 'joao']));
    }

    public function testExecuteValidationWithManyRules(): void
    {
        $rules = ['value' => ['notEmpty', 'notBlank', 'alpha']];
        $validator = new ValidationAnnotation($rules);
        $this->assertNull($validator->executeValidationInParameter([0 => 'joao']));
    }

    public function testExecuteValidationWithRulesWithParam(): void
    {
        $rules = ['value' => ['notEmpty', 'notBlank', 'alpha', 'length(3)']];
        $validator = new ValidationAnnotation($rules);
        $this->assertNull($validator->executeValidationInParameter([0 => 'joao']));
    }

    public function testGetValidationErrorsNullResult()
    {
        $rules = ['value' => ['notEmpty', 'notBlank', 'alpha', 'length(2)']];
        $validator = new ValidationAnnotation($rules);
        $validator->executeValidationInParameter([0 => 'joao']);
        $result = $validator->getValidationErrors();
        $this->assertEquals(null, $result);
    }

    public function testGetValidationErrorsSingleError()
    {
        $rules = ['value' => ['notEmpty', 'notBlank', 'alpha', 'length(2)', 'noWhitespace']];
        $validator = new ValidationAnnotation($rules);
        $validator->executeValidationInParameter([0 => 'joao do ceu']);
        $result = $validator->getValidationErrors();
        $this->assertEquals(['"joao do ceu" must not contain whitespace'], $result);
    }

    public function testGetValidationErrorsMultiErrors()
    {
        $rules = ['value' => ['notEmpty', 'notBlank', 'alpha', 'length(50)', 'noWhitespace']];
        $validator = new ValidationAnnotation($rules);
        $validator->executeValidationInParameter([0 => 'joao do ceu']);
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
