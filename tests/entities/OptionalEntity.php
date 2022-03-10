<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\entities;

use gugglegum\AbstractEntity\EntityInterface;
use gugglegum\AbstractEntity\EntityTrait;
use gugglegum\AbstractEntity\Exception;
use gugglegum\AbstractEntity\GettersAndSettersTrait;
use gugglegum\AbstractEntity\PlainObjectTrait;

class OptionalEntity implements EntityInterface
{
    use EntityTrait, GettersAndSettersTrait, PlainObjectTrait;

    private string $param1;

    private string $param2 = '123';

    private ?string $param3 = null;

    private $param4;

    private $param5 = null;

    /**
     * @return string
     */
    public function getParam1(): string
    {
        return $this->param1;
    }

    /**
     * @param string $param1
     * @return OptionalEntity
     */
    public function setParam1(string $param1): OptionalEntity
    {
        $this->param1 = $param1;
        return $this;
    }

    /**
     * @return string
     */
    public function getParam2(): string
    {
        return $this->param2;
    }

    /**
     * @param string $param2
     * @return OptionalEntity
     */
    public function setParam2(string $param2): OptionalEntity
    {
        $this->param2 = $param2;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getParam3(): ?string
    {
        return $this->param3;
    }

    /**
     * @param string|null $param3
     * @return OptionalEntity
     */
    public function setParam3(?string $param3): OptionalEntity
    {
        $this->param3 = $param3;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParam4(): mixed
    {
        return $this->param4;
    }

    /**
     * @param mixed $param4
     * @return OptionalEntity
     */
    public function setParam4(mixed $param4): static
    {
        $this->param4 = $param4;
        return $this;
    }

    /**
     * @return null
     */
    public function getParam5()
    {
        return $this->param5;
    }

    /**
     * @param mixed $param5
     * @return OptionalEntity
     */
    public function setParam5(mixed $param5)
    {
        $this->param5 = $param5;
        return $this;
    }

    /**
     * @param string $key
     * @return static
     */
    public function unsetAttribute(string $key): static
    {
        if (!$this->hasAttribute($key)) {
            throw new Exception("Attempt to set non-existing attribute \"{$key}\"");
        }
        unset($this->{$key});
        return $this;
    }
}
