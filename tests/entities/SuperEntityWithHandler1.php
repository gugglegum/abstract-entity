<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\entities;

use gugglegum\AbstractEntity\ArrayableInterface;

class SuperEntityWithHandler1 extends SuperEntity
{
    protected function toArrayObjectHandler(object $obj): array|object
    {
        if ($obj instanceof ArrayableInterface) {
            return $obj->toArray(\Closure::fromCallable([$this, 'toArrayObjectHandler'])); // <-- recursive toArray object handler for ArrayableInterface
        }
        if ($obj instanceof \stdClass) {
            return get_object_vars($obj);
        }
        return $obj;
    }
}
