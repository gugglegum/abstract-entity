<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\models;

use gugglegum\AbstractEntity\AbstractEntity;
use gugglegum\AbstractEntity\tests\CustomException;

/**
 * Custom User
 *
 * The same as User but uses associative array in private field `attributes` to store attribute values. Additionally
 * it redefines exception class to CustomException.
 *
 * @package gugglegum\AbstractEntity\tests\models
 */
class CustomUser extends AbstractEntity
{
    private $attributes = [
        'name' => null,
        'email' => null,
        'disabled' => false,
    ];

    /**
     * Constructor allows to initialize attribute values
     *
     * @param array $data           Associative array with [attribute => value] pairs
     */
    public function __construct(array $data = [])
    {
        $this->__setExceptionClass(CustomException::class);
        parent::__construct($data);
    }

    /**
     * @return string|null
     */
    public function getName()
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
    public function getEmail()
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
        return ['name', 'email', 'disabled'];
    }
}
