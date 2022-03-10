<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\entities;

use gugglegum\AbstractEntity\AbstractEntity;
use gugglegum\AbstractEntity\ArrayableInterface;
use gugglegum\AbstractEntity\EntityInterface;
use gugglegum\AbstractEntity\EntityTrait;
use gugglegum\AbstractEntity\GettersAndSettersTrait;
use gugglegum\AbstractEntity\tests\CustomException;

/**
 * Custom User
 *
 * The same as User but uses associative array in private field `attributes` to store attribute values. Additionally,
 * it redefines exception class to CustomException.
 */
class CustomUser implements EntityInterface
{
    use EntityTrait, GettersAndSettersTrait;

    private array $attributes = [
        'name' => null,
        'email' => null,
        'isAdmin' => false,
        'disabled' => false,
    ];

    /**
     * Constructor allows initializing attribute values
     *
     * @param array $data           Associative array with [attribute => value] pairs
     */
    public function __construct(array $data = [])
    {
        $this->__setExceptionClass(CustomException::class);
        $this->setFromArray($data);
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->attributes['name'];
    }

    /**
     * @param string $name
     * @return CustomUser
     */
    public function setName(string $name): self
    {
        $this->attributes['name'] = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->attributes['email'];
    }

    /**
     * @param string $email
     * @return CustomUser
     */
    public function setEmail(string $email): self
    {
        $this->attributes['email'] = $email;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->attributes['isAdmin'];
    }

    /**
     * @param bool $isAdmin
     * @return CustomUser
     */
    public function setIsAdmin(bool $isAdmin): self
    {
        $this->attributes['isAdmin'] = $isAdmin;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->attributes['disabled'];
    }

    /**
     * @param bool $disabled
     * @return CustomUser
     */
    public function setDisabled(bool $disabled): self
    {
        $this->attributes['disabled'] = $disabled;
        return $this;
    }

    /**
     * @return array
     */
    public static function getAttributeNames(): array
    {
        return ['name', 'email', 'isAdmin', 'disabled'];
    }


    /**
     * Check is attribute initialized
     *
     * @param string $key
     * @return bool
     */
    public function issetAttribute(string $key): bool
    {
        return isset($this->attributes[$key]);
    }
}
