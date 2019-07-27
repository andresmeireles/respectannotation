<?php declare(strict_types = 1);

namespace Andresmeireles\RespectAnnotation\Annotation;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

/**
 * Class RuleValidator
 * @package Andresmeireles\RespectAnnotation\Annotation
 */
class RuleValidator
{
    /**
     * @var array
     */
    private $rules;

    /**
     * @var array|null
     */
    private $errors;

    /**
     * @var array|null
     */
    private $allErrors;

//    public function __construct(array $rules)
//    {
//        $this->rules = $rules;
//    }

    public function useRules(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @param array $parameter
     */
    public function executeDefaultValidationInParameter(array $parameter): void
    {
        foreach ($this->rules as $rule) {
            $nameWithoutParenthesis = $this->clearFunctionName($rule);
            $ruleToTest = $this->getFunctionParameters($rule) !== '' ?
                v::{$nameWithoutParenthesis}($this->getFunctionParameters($rule)) :
                v::{$nameWithoutParenthesis}();
            $this->validateParamByRule($parameter, $ruleToTest);
        }
    }

    /**
     * @param array $parameter
     */
    public function executeOptionalValidationInParameter(array $parameter): void
    {
        foreach ($this->rules as $rule) {
            $nameWithoutParenthesis = $this->clearFunctionName($rule);
            $ruleToTest = $this->getFunctionParameters($rule) !== '' ?
                v::{$nameWithoutParenthesis}($this->getFunctionParameters($rule)) :
                v::{$nameWithoutParenthesis}();
            $optionalRuleToTest = v::optional($ruleToTest);
            $this->validateParamByRule($parameter, $optionalRuleToTest);
        }
    }

    /**
     * @param array $parameter
     */
    public function executeNotValidationInParameter(array $parameter): void
    {
        foreach ($this->rules as $rule) {
            $nameWithoutParenthesis = $this->clearFunctionName($rule);
            $ruleToTest = $this->getFunctionParameters($rule) !== '' ?
                v::{$nameWithoutParenthesis}($this->getFunctionParameters($rule)) :
                v::{$nameWithoutParenthesis}();
            $notRuleToTest = v::not($ruleToTest);
            $this->validateParamByRule($parameter, $notRuleToTest);
        }
    }

    /**
     * @param $param
     * @param v $rule
     */
    private function validateParamByRule(array $param, v $rule): void
    {
        $parameterKey = key($param);

        try {
            $rule->setName($parameterKey)->assert($param[$parameterKey]);
        } catch (NestedValidationException $err) {
            $err->findMessages([
                'numeric' => '{{name}} precisa ser um número.',
                'positive' => '{{name}} precisa ser positivo.',
                'notEmpty' => '{{name}} não pode ser vazio.',
                'notBlank' => '{{name}} não pode estar em branco.',
                'not' => '{{name}} não'
            ]);
            $this->errors[] = $err->getMessages()[0];
            $this->allErrors[] = $err->getMessages();
        }
    }

    /**
     * @param $function
     * @return string
     */
    private function getFunctionParameters($function): string
    {
        if (!strpos($function, '(')) {
            return '';
        }

        $openParenthesis = strpos($function, '(');
        $closeParenthesis = strrpos($function, ')');
        $funcParametersBody = substr($function, $openParenthesis + 1, ($closeParenthesis - $openParenthesis) - 1);

        return $funcParametersBody;
    }

    /**[
     * @param string $unclearFuncName
     * @return string
     */
    private function clearFunctionName(string $unclearFuncName): string
    {
        $parameterWithParenthesis = sprintf(
            '(%s)',
            $this->getFunctionParameters($unclearFuncName)
        );

        return str_replace($parameterWithParenthesis, '', $unclearFuncName);
    }

    /**
     * @return null|array
     */
    public function getValidationErrors(): ?array
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getAllValidationErrors(): ?array
    {
        return $this->allErrors;
    }
}
