<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity;

interface ArrayableInterface
{
    public function toArray(?callable $objectToArrayHandler = null): array;

    public function setFromArray(array $data);

    public static function fromArray(array $data);
}
