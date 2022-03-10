<?php /** @noinspection PhpUnused */

declare(strict_types=1);

use gugglegum\AbstractEntity\EntityInterface;
use gugglegum\AbstractEntity\EntityTrait;
use gugglegum\AbstractEntity\GettersAndSettersTrait;
use gugglegum\AbstractEntity\PlainObjectTrait;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * Example 1
 *
 * The simplest usage: create entity instance initializing it via associative array passed to the fromArray() static
 * method and then print its contents exported to array.
 */

/**
 * User
 *
 * A simple entity for user in some site. Just an example in tests.
 */
class User1 implements EntityInterface
{
    use EntityTrait,
        GettersAndSettersTrait,
        PlainObjectTrait;

    private string $name;
    private string $email;
    private bool $isAdmin = false;
    private bool $disabled = false;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): self
    {
        $this->disabled = $disabled;
        return $this;
    }
}

$user = User1::fromArray([
    'name' => 'John',
    'email' => 'john@example.com',
    'isAdmin' => false,
    'disabled' => true,
]);

var_dump($user->toArray());
