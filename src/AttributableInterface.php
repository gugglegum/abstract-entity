<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity;

interface AttributableInterface
{
    /**
     * Returns value of particular attribute by name
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute(string $key): mixed;

    /**
     * Sets particular attribute by name with specified value
     *
     * @param string $key
     * @param mixed  $value
     * @return static
     */
    public function setAttribute(string $key, mixed $value): static;

    /**
     * Checks whether some attribute exists
     *
     * @param string $key
     * @return bool
     */
    public static function hasAttribute(string $key): bool;

    /**
     * Returns a list of entity attribute names (used in `::getAttributes()` and `hasAttribute()`)
     *
     * @return string[]
     */
    public static function getAttributeNames(): array;

    /**
     * @return array
     */
    public static function getMandatoryAttributeNames(): array;

    /**
     * @param string $key
     * @return bool
     */
    public static function isMandatoryAttribute(string $key): bool;

    /**
     * @return bool
     */
    public function isConsistent(): bool;

    /**
     * @return array<string,mixed>
     */
    public function getAttributes(): array;

    /**
     * Initializes the model by values from associative array. Only attributes corresponding to passed keys will be set.
     *
     * @param array<string,mixed> $data     Associative array with [attribute => value] pairs
     * @return static
     */
    public function setAttributes(array $data): static;

    /**
     * Check is attribute initialized
     *
     * @param string $key
     * @return bool
     */
    public function issetAttribute(string $key): bool;

}
