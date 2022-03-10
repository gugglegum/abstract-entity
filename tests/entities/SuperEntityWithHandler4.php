<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\entities;

use gugglegum\AbstractEntity\ArrayableInterface;

class SuperEntityWithHandler4 extends SuperEntity
{
    protected function toArrayObjectHandler(object $obj): string|array|object
    {
        if ($obj instanceof ArrayableInterface) {
            return $obj->toArray(\Closure::fromCallable([$this, 'toArrayObjectHandler'])); // <-- recursive toArray object handler for ArrayableInterface
        }
        if ($obj instanceof \stdClass) {
            return array_map(function($value) {
                return is_object($value)
                    ? \Closure::fromCallable([$this, 'toArrayObjectHandler'])($value)  // <-- recursive toArray object handler for \stdClass
                    : $value;
            }, get_object_vars($obj));
        }
        if ($obj instanceof \DateTime) {
            return $obj->format('c');
        }
        return $obj;
    }
}
