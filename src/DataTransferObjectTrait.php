<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity;

trait DataTransferObjectTrait
{
    /**
     * Returns value of particular attribute by name
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute(string $key): mixed
    {
        if (!$this->hasAttribute($key)) {
            throw new ($this->__getExceptionClass())("Attempt to get non-existing attribute \"{$key}\"");
        }
        return $this->{$key};
    }

    /**
     * Sets particular attribute by name with specified value
     *
     * @param string $key
     * @param mixed  $value
     * @return static
     */
    public function setAttribute(string $key, mixed $value): static
    {
        if (!$this->hasAttribute($key)) {
            throw new ($this->__getExceptionClass())("Attempt to set non-existing attribute \"{$key}\"");
        }
        $this->{$key} = $value;
        return $this;
    }
}
