<?php declare(strict_types = 1);

namespace Andresmeireles\RespectAnnotation;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use ReflectionException;
use ReflectionProperty;

/**
 * Class RespectValidationAnnotation
 * @package App\Utils\Andresmei
 */
final class RespectValidationAnnotation
{
    private $allValidationErrors;

    /**
     * @param object $class
     * @return array|null
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function executeClassValidation(object $class): ?array
    {
        AnnotationRegistry::registerFile(__DIR__.'/ValidationAnnotation.php');

        $firstValidationError = [];
        $reader = new AnnotationReader();
        $reflectionClass = new \ReflectionClass($class);
        $props = $reflectionClass->getProperties();
        foreach ($props as $prop) {
            $reflecProp = new ReflectionProperty($class, $prop->getName());
            //$getMethodName = sprintf('get%s', ucfirst($reflecProp->getName()));
            $valueForValidation = $this->getClassPropertyValue($class, $reflecProp);
            $validations = $reader->getPropertyAnnotation($reflecProp, ValidationAnnotation::class);

            if ($validations instanceof ValidationAnnotation) {
                $validations->executeValidationInParameter([$reflecProp->getName() => $valueForValidation]);
                $firstValidationError[] = $validations->getValidationErrors();
                $this->allValidationErrors[] = $validations->getAllValidationErrors();
            }
        }
        $cleanedNullValues = $this->clearNullValues($firstValidationError);

        return $cleanedNullValues === null ? $cleanedNullValues : $this->putSameArrayMessages($cleanedNullValues);
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
    private function clearNullValues(array $validationValues): ?array
    {
        foreach ($validationValues as $key => $value) {
            if ($value === null) {
                unset($validationValues[$key]);
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

    /**
     * @return array|null
     */
    public function getAllErrorMessages(): ?array
    {
        return $this->clearNullValues($this->allValidationErrors);
    }
}
