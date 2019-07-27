<?php declare(strict_types = 1);

namespace Andresmeireles\RespectAnnotation\TestEntities;

use Andresmeireles\RespectAnnotation\ValidationAnnotation as Respect;

class OptionalEntity
{
    /**
     * @Respect(optrules={"noWhitespace"})
     */
    private $name;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return OptionalEntity
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
