<?php declare(strict_types = 1);

namespace Andresmeireles\RespectAnnotation;

use PHPUnit\Framework\TestCase;

class ValidationAnnotationTest extends TestCase
{
    public function testValidateParameterWithRulesOnly(): void
    {
        $rules = [
            'rules' => ['notEmpty']
        ];
        $validator = new ValidationAnnotation($rules);
        $result = $validator->validateParameter([0 => 'joao']);
        $this->assertEquals(['errors' => null, 'allErrors' => null], $result);
    }

    public function testValidateParameterWithRulesOnlyFail(): void
    {
        $rules = [
            'rules' => ['notEmpty']
        ];
        $validator = new ValidationAnnotation($rules);
        $result = $validator->validateParameter(['jose' => '']);
        $this->assertEquals(
            [
                'errors' => ['jose não pode ser vazio.'],
                'allErrors' => [['jose não pode ser vazio.']]],
            $result
        );
    }

    public function testValidateParameterWithRulesOnlyMultipleFail(): void
    {
        $rules = [
            'rules' => ['noWhitespace', 'numeric']
        ];
        $validator = new ValidationAnnotation($rules);
        $result = $validator->validateParameter(['jose' => 'fred H']);
        $this->assertEquals(
            [
                'errors' => ['jose must not contain whitespace', 'jose precisa ser um número.'],
                'allErrors' => [
                        ['jose must not contain whitespace'],
                        ['jose precisa ser um número.']
                ]
            ],
            $result
        );
    }

    public function testValidateParameterWithOptionalRulesOnly(): void
    {
        $rules = [
            'optrules' => ['alpha']
        ];
        $validator = new ValidationAnnotation($rules);
        $result = $validator->validateParameter([0 => '']);
        $this->assertEquals(['errors' => null, 'allErrors' => null], $result);
    }

    public function testValidateParameterWithOptionalRulesOnlyFail(): void
    {
        $rules = [
            'optrules' => ['numeric']
        ];
        $validator = new ValidationAnnotation($rules);
        $result = $validator->validateParameter([0 => 'jose']);
        $assertResult = [
            'errors' => ['"jose" precisa ser um número.'],
            'allErrors' => [

                    ['"jose" precisa ser um número.']

            ]
        ];
        $this->assertEquals($assertResult, $result);
    }

    public function testValidateParameterWithOptionalRulesOnlyMultipleFail(): void
    {
        $rules = [
            'optrules' => ['numeric', 'noWhitespace']
        ];
        $validator = new ValidationAnnotation($rules);
        $result = $validator->validateParameter([0 => 'jose de arimateia']);
        $assertResult = [
            'errors' => [
                '"jose de arimateia" precisa ser um número.',
                '"jose de arimateia" must not contain whitespace'
            ],
            'allErrors' => [
                    ['"jose de arimateia" precisa ser um número.'],
                    ['"jose de arimateia" must not contain whitespace']
            ]
        ];
        $this->assertEquals($assertResult, $result);
    }

    public function testValidateParameterWithNotRulesOnly(): void
    {
        $rules = [
            'notrules' => ['alpha']
        ];
        $validator = new ValidationAnnotation($rules);
        $result = $validator->validateParameter([0 => '']);
        $this->assertEquals(['errors' => null, 'allErrors' => null], $result);
    }

    public function testValidateParameterWithNotRulesOnlyFail(): void
    {
        $rules = [
            'notrules' => ['numeric']
        ];
        $validator = new ValidationAnnotation($rules);
        $result = $validator->validateParameter([0 => '123']);
        $assertResult = [
            'errors' => ['"123" precisa ser um número.'],
            'allErrors' => [
                    ['"123" precisa ser um número.']
            ]
        ];
        $this->assertEquals($assertResult, $result);
    }

    public function testValidateParameterWithOptionalRulesNotMultipleFail(): void
    {
        $rules = [
            'notrules' => ['numeric', 'noWhitespace']
        ];
        $validator = new ValidationAnnotation($rules);
        $result = $validator->validateParameter([0 => 2]);
        $assertResult = [
            'errors' => [
                '2 precisa ser um número.',
                '2 must not not contain whitespace'
            ],
            'allErrors' => [
                    ['2 precisa ser um número.'],
                    ['2 must not not contain whitespace']
            ]
        ];
        $this->assertEquals($assertResult, $result);
    }

    public function testValidateParameterWithMultipleRules(): void
    {
        $rules = [
            'rules' => ['notEmpty'],
            'notrules' => ['numeric'],
            'optional' => ['noWhitespace']
        ];
        $validator = new ValidationAnnotation($rules);
        $result = $validator->validateParameter(['hexer']);
        $assertResult = ['errors' => null, 'allErrors' => null];
        $this->assertEquals($assertResult, $result);
    }

    public function testValidateParameterWithMultipleRulesFail(): void
    {
        $rules = [
            'rules' => ['alpha'],
            'notrules' => ['numeric'],
            'optrules' => ['alpha']
        ];
        $validator = new ValidationAnnotation($rules);
        $result = $validator->validateParameter(['jose' => '88']);
        $assertResult = [
            'errors' => [
                'jose must contain only letters (a-z)',
                'jose must contain only letters (a-z)',
                'jose precisa ser um número.'
            ],
            'allErrors' => [
                   ['jose must contain only letters (a-z)']
                ,
                   ['jose must contain only letters (a-z)']
                ,
                   ['jose precisa ser um número.']

            ]
        ];
        $this->assertEquals($assertResult, $result);
    }
}
