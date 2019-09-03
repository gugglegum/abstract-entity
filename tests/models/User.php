<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\models;

use gugglegum\AbstractEntity\AbstractEntity;

/**
 * User
 *
 * A simple model for user in some site. Just an example in tests.
 *
 * @package gugglegum\AbstractEntity\tests\models
 */
class User extends AbstractEntity
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var bool
     */
    private $isAdmin = false;

    /**
     * @var bool
     */
    private $disabled = false;

    /**
     * A static property that is added just to test that static properties are not listed by `getAttributeNames()`.
     */
    private static $someStaticProperty;

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     * @return self
     */
    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     * @return self
     */
    public function setDisabled(bool $disabled): self
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * @return mixed
     */
    public static function getSomeStaticProperty()
    {
        return self::$someStaticProperty;
    }

    /**
     * @param mixed $someStaticProperty
     */
    public static function setSomeStaticProperty($someStaticProperty)
    {
        self::$someStaticProperty = $someStaticProperty;
    }
}
