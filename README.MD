# Abstract Entity Class

This class is used as a base class for classes with entities (models). An entities extended from
this base class will get the methods to work with attributes through getters and setters. The
constructor method allow to pass an array which will be used to initialize new object. It also
contains `getGetter()` and `getSetter()` methods to convert an attribute name to getter and setter
methods. For every entity class property (which should be private) you need to generate a pair of
getter and setter. You may use automatic generation of these methods in your IDE (I use the PhpStorm).

The abstract entity gets a list of attributes of concrete entity from its properties (using
`ReflectionClass`). You may redefine this method to add some additional handling. You may add some virtual
attributes which are not present as class properties or remove some special properties which should not be
used as attributes.

## Requirements

This class written to be executed on PHP version 7.0 or better. It uses `define(strict_types=1)`
and scalar types in methods definition.

## Example

An entity class example:

```
<?php

declare(strict_types=1);

use gugglegum\AbstractEntity\AbstractEntity;

class User extends AbstractEntity
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var bool
     */
    private $disabled = false;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): self
    {
        $this->name = $name;
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
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
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
     * @return User
     */
    public function setDisabled(bool $disabled): self
    {
        $this->disabled = $disabled;
        return $this;
    }
}
```

Test usage:

```
<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/User.php';

$user = User::fromArray([
    'name' => 'John Smith',
    'email' => 'john.smith@example.com',
    'disabled' => true,
]);

var_dump($user->toArray());

```
