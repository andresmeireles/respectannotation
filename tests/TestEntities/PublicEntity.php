<?php declare(strict_types = 1);

namespace Andresmeireles\RespectAnnotation\TestEntities;

use Andresmeireles\RespectAnnotation\ValidationAnnotation as Respect;

class PublicEntity
{
    /**
     * @Respect({"noWhitespace", "alpha"})
     */
    public $name;

    /**
     * @Respect({"alpha", "noWhitespace"})
     */
    public $lastName;

    /**
     * @Respect({"notBlank", "notEmpty", "numeric"})
     */
    public $age;
}
