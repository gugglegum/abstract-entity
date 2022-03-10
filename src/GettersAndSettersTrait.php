<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity;

trait GettersAndSettersTrait
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
        return $this->{$this->getGetter($key)}();
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
        $this->{$this->getSetter($key)}($value);
        return $this;
    }

    /**
     * Returns getter name for particular attribute name
     *
     * @param string $attributeName
     * @return string
     */
    protected function getGetter(string $attributeName): string
    {
        $cameCaseAttrName = implode('', array_map(function(string $word) { return ucfirst($word); }, explode('_', $attributeName)));
        $possibleGetterMethods = [];
        if (method_exists($this, ($possibleGetterMethods[] = 'get' . $cameCaseAttrName))) {
            return array_pop($possibleGetterMethods);
        }
        if (method_exists($this, ($possibleGetterMethods[] = 'is' . $cameCaseAttrName))) {
            return array_pop($possibleGetterMethods);
        }
        if (preg_match('/^Is[A-Z\d]/', $cameCaseAttrName)) {
            if (method_exists($this, ($possibleGetterMethods[] = lcfirst($cameCaseAttrName)))) {
                return array_pop($possibleGetterMethods);
            }
        }
        throw new ($this->__getExceptionClass())("Can't find getter method " . implode('() or ', $possibleGetterMethods) . "() for attribute \"{$attributeName}\" in " . get_class($this));
    }

    /**
     * Returns setter name for particular attribute name
     *
     * @param string $attributeName
     * @return string
     */
    protected function getSetter(string $attributeName): string
    {
        $cameCaseAttrName = implode('', array_map(function(string $word) { return ucfirst($word); }, explode('_', $attributeName)));
        $setter = 'set' . $cameCaseAttrName;
        if (!method_exists($this, $setter)) {
            throw new ($this->__getExceptionClass())("Can't find setter method {$setter}() for attribute \"{$attributeName}\" in " . get_class($this));
        }
        return $setter;
    }
}
