<?php

namespace Andresmeireles\RespectAnnotation\TestEntities;

use Andresmeireles\RespectAnnotation\ValidationAnnotation as Respect;

class NotEntity
{
    /**
     * @Respect(notrules={"noWhitespace"})
     */
    public $name;
}
