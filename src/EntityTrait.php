<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity;

use JetBrains\PhpStorm\Pure;

/**
 * Base class for entities, provides basic methods for accessing attributes via getters and setters, contains
 * reflection to convert attribute name into getter or setter. Every attribute must have both getter and setter.
 */
trait EntityTrait
{
    /**
     * Custom exception class. By default, \gugglegum\AbstractEntity\Exception class will be thrown on error. But you
     * may define your own exception class for your models.
     *
     * This property beginning from "__" to reduce possible collision with user-defined properties names.
     *
     * @var string|null
     */
    private ?string $__exceptionClass = null;

    /**
     * The same as `attributeNames` but list of attribute names represented as associative array with NULL values. It's
     * used by `hasAttribute()` method. Usage of associative array little faster than ordered array to search by
     * value.
     *
     * @var array<string,array<string,null>>
     */
    private static array $_attributeNamesKeys = [];

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
    public function __setExceptionClass(string $__exceptionClass)
    {
        $this->__exceptionClass = $__exceptionClass;
    }

    /**
     * Creates new entity instance and initializes it with values from array. Actually shortcut for constructor.
     *
     * @param array<string,mixed> $data     Associative array with [attribute => value] pairs
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return (new static())->setFromArray($data);
    }

    /**
     * Initializes the model by values from associative array. Only attributes corresponding to passed keys will be set.
     *
     * @param array<string,mixed> $data     Associative array with [attribute => value] pairs
     * @return static
     */
    public function setFromArray(array $data): static
    {
        foreach ($data as $k => $v) {
            $this->setAttribute($k, $v);
        }
        return $this;
    }

    /**
     * Initializes the model by values from associative array. Only attributes corresponding to passed keys will be set.
     *
     * @param array<string,mixed> $data     Associative array with [attribute => value] pairs
     * @return static
     */
    public function setAttributes(array $data): static
    {
        return $this->setFromArray($data);
    }

    /**
     * Returns a list of entity attribute names (used in `::getAttributes()` and `hasAttribute()`)
     *
     * @return string[]
     */
    abstract public static function getAttributeNames(): array;

    /**
     * Returns value of particular attribute by name
     *
     * @param string $key
     * @return mixed
     */
    abstract public function getAttribute(string $key): mixed;

    /**
     * Sets particular attribute by name with specified value
     *
     * @param string $key
     * @param mixed  $value
     * @return static
     */
    abstract public function setAttribute(string $key, mixed $value): static;

    /**
     * @return array<string,mixed>
     */
    public function getAttributes(): array
    {
        $data = [];
        foreach (static::getAttributeNames() as $attributeName) {
            if ($this->issetAttribute($attributeName) || $this->isMandatoryAttribute($attributeName)) {
                $data[$attributeName] = $this->getAttribute($attributeName);
            }
        }
        return $data;
    }

    /**
     * @return array
     */
    public static function getMandatoryAttributeNames(): array
    {
        return [];
    }

    /**
     * @param string $key
     * @return bool
     */
    #[Pure] public static function isMandatoryAttribute(string $key): bool
    {
        return in_array($key, static::getMandatoryAttributeNames());
    }

    /**
     * @return bool
     */
    public function isConsistent(): bool
    {
        foreach (static::getMandatoryAttributeNames() as $attributeName) {
            if (!$this->issetAttribute($attributeName)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Checks whether some attribute exists
     *
     * @param string $key
     * @return bool
     */
    public static function hasAttribute(string $key): bool
    {
        if (!array_key_exists(static::class, self::$_attributeNamesKeys)) {
            self::$_attributeNamesKeys[static::class] = array_fill_keys(static::getAttributeNames(), null);
        }
        return array_key_exists($key, self::$_attributeNamesKeys[static::class]);
    }

    /**
     * Check is attribute initialized
     *
     * @param string $key
     * @return bool
     */
    abstract public function issetAttribute(string $key): bool;

    /**
     * Returns entity as associative array. Key of array is attribute names.
     *
     * @return array<string,mixed>      Associative array with [attributeName => attributeValue] pairs
     */
    public function toArray(?callable $objectToArrayHandler = null): array
    {
        $toArray = function(array &$data) use (&$toArray, $objectToArrayHandler) {
            foreach ($data as &$value) {
                if (is_object($value)) {
                    if ($objectToArrayHandler === null) {
                        $value = $this->toArrayObjectHandler($value);
                    } else {
                        $value = $objectToArrayHandler($value);
                    }
                } elseif (is_array($value)) {
                    $toArray($value);
                }
            }
        };
        $data = $this->getAttributes();
        $toArray($data);
        return $data;
    }

    /** @noinspection PhpMixedReturnTypeCanBeReducedInspection */
    protected function toArrayObjectHandler(object $obj): mixed
    {
        return $obj instanceof ArrayableInterface ? $obj->toArray() : $obj;
    }

    /**
     * Method for JsonSerializable interface
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

}
