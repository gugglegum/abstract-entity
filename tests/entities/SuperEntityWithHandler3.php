<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\entities;

use gugglegum\AbstractEntity\ArrayableInterface;

class SuperEntityWithHandler3 extends SuperEntity
{
    protected function toArrayObjectHandler(object $obj): mixed
    {
        if ($obj instanceof ArrayableInterface) {
            return $obj->toArray(\Closure::fromCallable([$this, 'toArrayObjectHandler'])); // <-- recursive toArray object handler for ArrayableInterface
        }
        if ($obj instanceof \stdClass) {
            return get_object_vars($obj);
        }
        if ($obj instanceof \DateTime) {
            return $obj->format('c');
        }
        return $obj;

    }
}
