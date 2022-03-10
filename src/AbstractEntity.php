<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity;

/**
 * Base class for entities, provides basic methods for accessing attributes via getters and setters, contains
 * reflection to convert attribute name into getter or setter. Every attribute must have both getter and setter.
 */
abstract class AbstractEntity implements EntityInterface
{
    use EntityTrait, GettersAndSettersTrait, PlainObjectTrait;

    /**
     * Constructor allows initializing attribute values
     *
     * @param array $data           Associative array with [attribute => value] pairs
     */
    public function __construct(array $data = [])
    {
        $this->setFromArray($data);
    }
}
