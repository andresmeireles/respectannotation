<?php declare(strict_types = 1);

namespace Andresmeireles\RespectAnnotation\TestEntities;

use Andresmeireles\RespectAnnotation\ValidationAnnotation as Respect;

class PublicEntity
{
    /**
     * @Respect(RULES={"noWhitespace", "alpha"})
     */
    public $name;

    /**
     * @Respect(RULES={"alpha", "noWhitespace"})
     */
    public $lastName;

    /**
     * @Respect(RULES={"notBlank", "notEmpty", "numeric"})
     */
    public $age;
}
