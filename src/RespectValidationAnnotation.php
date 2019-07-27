<?php declare(strict_types = 1);

namespace Andresmeireles\RespectAnnotation;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use ReflectionException;
use ReflectionProperty;
use function PHPUnit\Framework\StaticAnalysis\HappyPath\AssertNull\consume;

/**
 * Class RespectValidationAnnotation
 * @package App\Utils\Andresmei
 */
final class RespectValidationAnnotation
{
    private $errors;

    private $allErrors;

    /**
     * @param object $class
     * @return array|null
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function executeClassValidation(object $class): ?array
    {
        AnnotationRegistry::registerFile(__DIR__ . '/ValidationAnnotation.php');

        $reader = new AnnotationReader();
        $reflectionClass = new \ReflectionClass($class);
        $props = $reflectionClass->getProperties();
        foreach ($props as $prop) {
            $reflecProp = new ReflectionProperty($class, $prop->getName());
            $valueForValidation = $this->getClassPropertyValue($class, $reflecProp);
            $validations = $reader->getPropertyAnnotation($reflecProp, ValidationAnnotation::class);
            if ($validations instanceof ValidationAnnotation) {
                $errors = $validations->validateParameter(
                    [$reflecProp->getName() => $valueForValidation]
                );
                $this->allocateErrors($errors);
            }
        }
        $cleanedMessages = $this->clearNullValues($this->errors);

        return $cleanedMessages === null ? $cleanedMessages : $this->putSameArrayMessages($cleanedMessages);
    }

    private function allocateErrors(array $errors): void
    {
        $this->errors[] = $errors['errors'];
        $this->allErrors[] = $errors['allErrors'];
    }

    /**
     * @return array|null
     */
    public function getAllErrorMessages(): ?array
    {
        return $this->clearNullValues($this->allErrors);
    }

    /**
     * @param object $class
     * @param ReflectionProperty $property
     * @return mixed
     */
    private function getClassPropertyValue(object $class, ReflectionProperty $property)
    {
        if ($property->isPublic()) {
            $propertyName = $property->getName();
            return $class->{$propertyName};
        }

        $methodName = sprintf('get%s', ucfirst($property->getName()));

        return $class->{$methodName}();
    }


    /**
     * @param array $validationValues
     * @return array|null
     */
    private function clearNullValues(?array $validationValues): ?array
    {
        if ($validationValues === null) {
            return null;
        }

        foreach ($validationValues as $key => $value) {
            if ($value === null) {
                unset($validationValues[$key]);
                continue;
            }
            if (is_array($value)) {
                $this->clearNullValues($value);
            }
        }
        foreach ($validationValues as $key => $value) {
            if ($value === null) {
                unset($validationValues[$key]);
                continue;
            }
            if (is_array($value)) {
                $this->clearNullValues($value);
            }
        }

        return $validationValues === [] ? null : $validationValues;
    }

    /**
     * @param array $nestedMessages
     * @return array
     */
    private function putSameArrayMessages(array $nestedMessages): array
    {
        $listOfMessages = [];
        array_walk($nestedMessages, static function ($message) use (&$listOfMessages) {
            array_map(static function ($m) use (&$listOfMessages) {
                $listOfMessages[] = $m;
            }, $message);
        });

        return $listOfMessages;
    }
}
