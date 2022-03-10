<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\entities;

use gugglegum\AbstractEntity\EntityInterface;
use gugglegum\AbstractEntity\EntityTrait;
use gugglegum\AbstractEntity\GettersAndSettersTrait;
use gugglegum\AbstractEntity\PlainObjectTrait;

class MandatoryEntity implements EntityInterface
{
    use EntityTrait, GettersAndSettersTrait, PlainObjectTrait;

    private string $login;

    private string $password;

    private string $email;

    private string $phone;

    private int $yearOfBirth;

    /**
     * @return array
     */
    public static function getMandatoryAttributeNames(): array
    {
        return [
            'login',
            'password',
        ];
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return static
     */
    public function setLogin(string $login): static
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return static
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return static
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return static
     */
    public function setPhone(string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return int
     */
    public function getYearOfBirth(): int
    {
        return $this->yearOfBirth;
    }

    /**
     * @param int $yearOfBirth
     * @return static
     */
    public function setYearOfBirth(int $yearOfBirth): static
    {
        $this->yearOfBirth = $yearOfBirth;
        return $this;
    }
}
