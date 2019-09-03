<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity;

use ReflectionClass;
use ReflectionException;

/**
 * Base class for entities, provides basic methods for accessing attributes via getters and setters, contains
 * reflection to convert attribute name into getter or setter. Every attribute must have both getter and setter.
 * To work properly the method `getAttributeNames()` should return complete list (array) of attribute names
 * (it's needed in `toArray()` method). This method already implemented in this base class, it uses the `ReflectionClass`
 * to retrieve all class properties (including inherited properties) and returns its names. So your getters and setters
 * should work with these properties. You may override this method to add some attributes not listed in properties
 * or to remove some attributes that should not be referenced with some properties. If you would like to store
 * attributes not in class properties (for example, in one single property with associative array) you should redefine
 * `getAttributeNames()` method and make all getters and setters to store data as you want.
 *
 * @package gugglegum\AbstractEntity
 */
abstract class AbstractEntity
{
    /**
     * Custom exception class. By default \gugglegum\AbstractEntity\Exception class will be thrown on error. But you
     * may define your own exception class for your models.
     *
     * This property beginning from "__" to reduce possible collision with user-defined attribute names.
     *
     * @var string|null
     */
    private $__exceptionClass;

    /**
     * A two-dimensional associative array contains keys with concrete class models on 1st level and ordered array with
     * list of attribute names. These lists are populated by `getAttributeNames()` method and then used as a cache for
     * list to do not retrieve attributes every time using `\ReflectionClass`.
     *
     * @var array
     */
    private static $attributeNames = [];

    /**
     * The same as `attributeNames` but list of attribute names represented as associative array with NULL values. It's
     * used by `hasAttribute()` method. Usage of associative array little bit faster than ordered array to search by
     * value.
     *
     * @var array
     */
    private static $attributeNamesKeys = [];

    /**
     * Constructor allows to initialize attribute values
     *
     * @param array $data           Associative array with [attribute => value] pairs
     */
    public function __construct(array $data = [])
    {
        $this->setFromArray($data);
    }

    /**
     * Returns currently used exception class if some error occurs.
     *
     * @return string
     */
    public function __getExceptionClass(): string
    {
        if ($this->__exceptionClass !== null) {
            return $this->__exceptionClass;
        } else {
            return Exception::class;
        }
    }

    /**
     * Sets alternative user-defined exception class
     *
     * @param string $__exceptionClass
     */
    public function __setExceptionClass($__exceptionClass)
    {
        $this->__exceptionClass = $__exceptionClass;
    }

    /**
     * Creates new entity instance and initializes it with values from array. Actually shortcut for constructor.
     *
     * @param array $data Associative array with [attribute => value] pairs
     * @return static
     */
    public static function fromArray(array $data)
    {
        return (new static($data));
    }

    /**
     * Initializes the model by values from associative array. Only attributes corresponding to passed keys will be set.
     *
     * @param array $data Associative array with [attribute => value] pairs
     * @return self
     */
    public function setFromArray(array $data): self
    {
        foreach ($data as $k => $v) {
            $this->setAttribute($k, $v);
        }
        return $this;
    }

    /**
     * Returns a list of properties of specified class.
     *
     * @param string $className
     * @return string[]
     */
    protected static function getClassProperties(string $className): array
    {
        try {
            $reflectionClass = new ReflectionClass($className);
        } catch (ReflectionException $e) {
            return [];
        }
        $attributeNames = [];
        foreach ($reflectionClass->getProperties() as $property) {
            if ($property->isStatic() || $property->getName() === '__exceptionClass') {
                continue;
            }
            $attributeNames[] = $property->getName();
        }
        return $attributeNames;
    }

    /**
     * Returns a list of entity attribute names (used in `AbstractEntity::toArray()`)
     *
     * @return string[]
     */
    public static function getAttributeNames(): array
    {
        if (!array_key_exists(static::class, self::$attributeNames)) {
            self::$attributeNames[static::class] = [];
            $class = static::class;
            do {
                self::$attributeNames[static::class] = array_merge(self::getClassProperties($class), self::$attributeNames[static::class]);
                $class = get_parent_class($class);
            } while ($class !== false);
            // Remove duplicates if some child class contains the same property as parent class
            self::$attributeNames[static::class] = array_unique(self::$attributeNames[static::class]);
        }
        return self::$attributeNames[static::class];
    }

    /**
     * Checks whether some attribute exists
     *
     * @param string $key
     * @return bool
     */
    public static function hasAttribute(string $key): bool
    {
        if (!array_key_exists(static::class, self::$attributeNamesKeys)) {
            self::$attributeNamesKeys[static::class] = array_fill_keys(static::getAttributeNames(), null);
        }
        return array_key_exists($key, self::$attributeNamesKeys[static::class]);
    }

    /**
     * Returns value of particular attribute by name
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute(string $key)
    {
        if (!$this->hasAttribute($key)) {
            $exceptionClass = $this->__getExceptionClass();
            throw new $exceptionClass("Attempt to get non-existing attribute \"{$key}\"");
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
     */
    public function setAttribute(string $key, $value): self
    {
        if (!$this->hasAttribute($key)) {
            $exceptionClass = $this->__getExceptionClass();
            throw new $exceptionClass("Attempt to set non-existing attribute \"{$key}\"");
        }
        $setter = $this->getSetter($key);
        $this->{$setter}($value);
        return $this;
    }

    /**
     * Returns entity as associative array. Key of array is attribute names.
     *
     * @return array                Associative array with [attributeName => attributeValue] pairs
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
     */
    protected function getGetter(string $attributeName): string
    {
        $possibleGetterMethods = [];
        if (method_exists($this, ($possibleGetterMethods[] = 'get' . ucfirst($attributeName)))) {
            return array_pop($possibleGetterMethods);
        }
        if (method_exists($this, ($possibleGetterMethods[] = 'is' . ucfirst($attributeName)))) {
            return array_pop($possibleGetterMethods);
        }
        if (preg_match('/^[iI]s[A-Z]/', $attributeName)) {
            if (method_exists($this, ($possibleGetterMethods[] = $attributeName))) {
                return array_pop($possibleGetterMethods);
            }
        }
        $exceptionClass = $this->__getExceptionClass();
        throw new $exceptionClass("Can't find getter method " . implode('() or ', $possibleGetterMethods) . "() for attribute \"{$attributeName}\" in " . get_class($this));
    }

    /**
     * Returns setter name for particular attribute name
     *
     * @param string $attributeName
     * @return string
     */
    protected function getSetter(string $attributeName): string
    {
        $setter = 'set' . ucfirst($attributeName);
        if (!method_exists($this, $setter)) {
            $exceptionClass = $this->__getExceptionClass();
            throw new $exceptionClass("Can't find setter method {$setter}() for attribute \"{$attributeName}\" in " . get_class($this));
        }
        return $setter;
    }
}
