<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\entities;

use gugglegum\AbstractEntity\DataTransferObjectTrait;
use gugglegum\AbstractEntity\EntityInterface;
use gugglegum\AbstractEntity\EntityTrait;
use gugglegum\AbstractEntity\PlainObjectTrait;

class DTOUser implements EntityInterface
{
    use EntityTrait, DataTransferObjectTrait, PlainObjectTrait;

    /**
     * @var string|null
     */
    public $name;

    /**
     * @var string|null
     */
    public $email;

    /**
     * @var bool
     */
    public $isAdmin = false;

    /**
     * @var bool
     */
    public $disabled = false;

    /**
     * A static property that is added just to test that static properties are not listed by `getAttributeNames()`.
     */
    public static $someStaticProperty;

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
