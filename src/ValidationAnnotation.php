<?php declare(strict_types = 1);

namespace Andresmeireles\RespectAnnotation;

use Andresmeireles\RespectAnnotation\Annotation\RuleValidator;

/**
 * Class ValidationAnnotation
 * @Annotation
 * @Target("PROPERTY"):
 */
final class ValidationAnnotation
{
    /**
     * @var array
     */
    private $rules;

    /**
     * @var array|null
     */
    private $optionalRules;

    /**
     * @var array|null
     */
    private $notRules;

    /**
     * @var array
     */
    private $validationErrors = ['errors' => null, 'allErrors' => null];

    public function __construct(?array $validationParameters)
    {
        $validationParameters = array_change_key_case($validationParameters, CASE_LOWER);
        $this->rules = $validationParameters['rules'] ?? null;
        $this->optionalRules = $validationParameters['optrules'] ?? null;
        $this->notRules = $validationParameters['notrules'] ?? null;
    }

    /**
     * @param array $parameter
     * @return array
     */
    public function validateParameter(array $parameter): array
    {
        $validator = new RuleValidator();

        if ($this->rules !== null) {
            $validator->useRules($this->rules);
            $validator->executeDefaultValidationInParameter($parameter);
            $this->addErrorsIfExists($validator);
        }

        if ($this->optionalRules !== null) {
            $validator->useRules($this->optionalRules);
            $validator->executeOptionalValidationInParameter($parameter);
            $this->addErrorsIfExists($validator);
        }

        if ($this->notRules !== null) {
            $validator->useRules($this->notRules);
            $validator->executeNotValidationInParameter($parameter);
            $this->addErrorsIfExists($validator);
        }

        return $this->validationErrors;
    }

    /**
     * @param RuleValidator $validator
     */
    private function addErrorsIfExists(RuleValidator $validator): void
    {
        $error = $validator->getValidationErrors();
        $allErrors = $validator->getAllValidationErrors();

        if ($error !== null) {
            $this->validationErrors['errors'] = $error;
        }

        if ($allErrors !== null) {
            $this->validationErrors['allErrors'] = $allErrors;
        }
    }
}
