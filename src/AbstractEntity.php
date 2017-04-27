<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity;

/**
 * Base class for entities, provides basic methods for accessing attributes via getters and setters, contains
 * reflection to convert attribute name into getter or setter. Every attribute must have both getter and setter.
 * Every entity must have static method `getAttributeNames()` which returns the list of attributes. It's used in
 * `toArray()` method. Attributes may be stored as private properties or as associative array property - it's
 * fully defined by getters and setters.
 *
 * @package gugglegum\AbstractEntity
 */
abstract class AbstractEntity
{
    /**
     * Constructor allows to initialize attribute values
     *
     * @param array $data           Associative array with [attribute => value] pairs
     * @throws Exception
     */
    public function __construct(array $data = [])
    {
        $this->setFromArray($data);
    }

    /**
     * Creates new entity instance and initializes it with values from array. Actually shortcut for constructor.
     *
     * @param array $data Associative array with [attribute => value] pairs
     * @return static
     * @throws Exception
     */
    public static function fromArray(array $data): self
    {
        return (new static($data));
    }

    /**
     * Initializes the model by values from associative array. Only attributes corresponding to passed keys will be set.
     *
     * @param array $data Associative array with [attribute => value] pairs
     * @return self
     * @throws Exception
     */
    public function setFromArray(array $data): self
    {
        foreach ($data as $k => $v) {
            $this->setAttribute($k, $v);
        }
        return $this;
    }

    /**
     * Returns a list of entity attribute names (used in `AbstractEntity::toArray()`)
     *
     * @return string[]
     */
    public static function getAttributeNames(): array
    {
        $reflectionClass = new \ReflectionClass(static::class);
        $attributeNames = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $attributeNames[] = $property->getName();
        }
        return $attributeNames;
    }

    /**
     * Checks whether some attribute exists
     *
     * @param string $key
     * @return bool
     */
    public function hasAttribute(string $key): bool
    {
        static $attributesHash;
        if ($attributesHash === null) {
            $attributesHash = array_fill_keys(static::getAttributeNames(), null);
        }
        return array_key_exists($key, $attributesHash);
    }

    /**
     * Returns value of particular attribute by name
     *
     * @param string $key
     * @return mixed
     * @throws Exception
     */
    public function getAttribute(string $key)
    {
        if (!$this->hasAttribute($key)) {
            throw new Exception("Attempt to get non-existing attribute \"{$key}\"");
        }
        $getter = $this->getGetter($key);
        return $this->{$getter}();
    }

    /**
     * Sets particular attribute by name with specified value
     *
     * @param string $key
     * @param mixed  $value
     * @return self
     * @throws Exception
     */
    public function setAttribute(string $key, $value): self
    {
        if (!$this->hasAttribute($key)) {
            throw new Exception("Attempt to set non-existing attribute \"{$key}\"");
        }
        $setter = $this->getSetter($key);
        $this->{$setter}($value);
        return $this;
    }

    /**
     * Returns entity as associative array. Key of array is attribute names.
     *
     * @return array                Associative array with [attributeName => attributeValue] pairs
     * @throws Exception
     */
    public function toArray(): array
    {
        $data = [];
        foreach (static::getAttributeNames() as $attributeName) {
            $getter = $this->getGetter($attributeName);
            $value = $this->{$getter}();

            if ($value instanceof AbstractEntity) {
                $value = $value->toArray();
            }

            if (is_array($value)) {
                foreach ($value as &$_value) {
                    if ($_value instanceof AbstractEntity) {
                        $_value = $_value->toArray();
                    }
                }
            }

            $data[$attributeName] = $value;
        }
        return $data;
    }

    /**
     * Returns getter name for particular attribute name
     *
     * @param string $attributeName
     * @return string
     * @throws Exception
     */
    protected function getGetter(string $attributeName): string
    {
        if (method_exists($this, ($getter1 = 'get' . ucfirst($attributeName)))) {
            return $getter1;
        } elseif (method_exists($this, ($getter2 = 'is' . ucfirst($attributeName)))) {
            return $getter2;
        } else {
            throw new Exception("Can't find getter method {$getter1}() or {$getter2}() for attribute \"{$attributeName}\" in " . get_class($this));
        }
    }

    /**
     * Returns setter name for particular attribute name
     *
     * @param string $attributeName
     * @return string
     * @throws Exception
     */
    protected function getSetter(string $attributeName): string
    {
        $setter = 'set' . ucfirst($attributeName);
        if (!method_exists($this, $setter)) {
            throw new Exception("Can't find setter method {$setter}() for attribute \"{$attributeName}\" in " . get_class($this));
        }
        return $setter;
    }
}
