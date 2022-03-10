<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity;

trait PlainObjectTrait
{
    /**
     * A two-dimensional associative array contains keys with concrete class models on 1st level and ordered array with
     * list of attribute names. These lists are populated by `getAttributeNames()` method and then used as a cache for
     * list to do not retrieve attributes via `\ReflectionClass` every time.
     *
     * @var array<string,string[]>
     */
    private static array $_attributeNames = [];

    /**
     * Check is attribute initialized
     *
     * @param string $key
     * @return bool
     */
    public function issetAttribute(string $key): bool
    {
        $class = static::class;
        $firstException = null;
        do {
            try {
                $rp = new \ReflectionProperty($class, $key);
                $rp->setAccessible(true);
                return $rp->isInitialized($this);
            } catch (\ReflectionException $e) {
                if ($firstException === null) {
                    $firstException = $e;
                }
                $class = get_parent_class($class);
                if ($class === false) {
                    throw new Exception($firstException->getMessage());
                }
            }
        } while (true);
    }

    /**
     * Returns a list of entity attribute names (used in `::getAttributes()`)
     *
     * @return string[]
     */
    public static function getAttributeNames(): array
    {
        if (!array_key_exists(static::class, static::$_attributeNames)) {
            static::$_attributeNames[static::class] = [];
            $class = static::class;
            do {
                static::$_attributeNames[static::class] = array_merge(self::getClassProperties($class), static::$_attributeNames[static::class]);
                $class = get_parent_class($class);
            } while ($class !== false);
            // Remove duplicates if some child class contains the same property as parent class
            static::$_attributeNames[static::class] = array_unique(static::$_attributeNames[static::class]);
        }
        return static::$_attributeNames[static::class];
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
            $reflectionClass = new \ReflectionClass($className);
        } catch (\ReflectionException) {
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
}
