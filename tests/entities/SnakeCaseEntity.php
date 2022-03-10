<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests\entities;

use gugglegum\AbstractEntity\EntityInterface;
use gugglegum\AbstractEntity\EntityTrait;
use gugglegum\AbstractEntity\GettersAndSettersTrait;
use gugglegum\AbstractEntity\PlainObjectTrait;

/**
 * Simple entity with underscored attributes. It will be used in tests to check valid getter and setter for attributes
 * with underscore characters (getter and setter methods will be camel-cased).
 */
class SnakeCaseEntity implements EntityInterface
{
    use EntityTrait, GettersAndSettersTrait, PlainObjectTrait;

    private string $user_name;

    private bool $is_deleted;

    private bool $is_email_confirmed;

    private int $bad_login_counter;

    private string $optional_parameter;

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->user_name;
    }

    /**
     * @param string $user_name
     * @return SnakeCaseEntity
     */
    public function setUserName(string $user_name): SnakeCaseEntity
    {
        $this->user_name = $user_name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isIsDeleted(): bool
    {
        return $this->is_deleted;
    }

    /**
     * @param bool $is_active
     * @return SnakeCaseEntity
     */
    public function setIsDeleted(bool $is_active): SnakeCaseEntity
    {
        $this->is_deleted = $is_active;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEmailConfirmed(): bool
    {
        return $this->is_email_confirmed;
    }

    /**
     * @param bool $is_email_confirmed
     * @return SnakeCaseEntity
     */
    public function setIsEmailConfirmed(bool $is_email_confirmed): SnakeCaseEntity
    {
        $this->is_email_confirmed = $is_email_confirmed;
        return $this;
    }

    /**
     * @return int
     */
    public function getBadLoginCounter(): int
    {
        return $this->bad_login_counter;
    }

    /**
     * @param int $bad_login_counter
     * @return SnakeCaseEntity
     */
    public function setBadLoginCounter(int $bad_login_counter): SnakeCaseEntity
    {
        $this->bad_login_counter = $bad_login_counter;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOptionalParameter(): ?string
    {
        return $this->optional_parameter;
    }

    /**
     * @param string|null $optional_parameter
     * @return SnakeCaseEntity
     */
    public function setOptionalParameter(?string $optional_parameter): SnakeCaseEntity
    {
        $this->optional_parameter = $optional_parameter;
        return $this;
    }
}
