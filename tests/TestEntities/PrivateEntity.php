<?php declare(strict_types = 1);

namespace Andresmeireles\RespectAnnotation\TestEntities;

use Andresmeireles\RespectAnnotation\ValidationAnnotation as Respect;

class PrivateEntity
{
    /**
     * @Respect(RULES={"noWhitespace", "alpha"})
     */
    private $name;

    /**
     * @Respect(RULES={"alpha", "noWhitespace"})
     */
    private $lastName;

    /**
     * @Respect(RULES={"notBlank", "notEmpty", "numeric"})
     */
    private $age;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return PrivateEntity
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     * @return PrivateEntity
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     * @return PrivateEntity
     */
    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }
}
