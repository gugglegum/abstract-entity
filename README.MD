# Abstract Entity Class

This class is used as a base class for classes with entities (models). An entities extended from
this base class will get the methods to work with attributes through getters and setters. The
constructor method allow to pass an array which will be used to initialize new object. It also
contains `getGetter()` and `getSetter()` methods to convert an attribute name to getter and setter
methods. For every entity class property (which should be private) you need to generate a pair of
getter and setter. You may use automatic generation of these methods in your IDE (I use the PhpStorm).

Every concrete entity class should have implementation of abstract method `getAttributeNames()` 
defined in abstract entity which simply returns a list (array) of attributes. This is used in
the `toArray()` method to iterate attributes. Since attributes of concrete entity are private the base
class can't get them via `get_class_vars()` or `get_object_vars()`. Actually you don't need to enumerate
all attributes (properties) manually, you may use `get_class_vars()` method to enumerate all properties
automatically:

```
    /**
     * Returns list of model's attribute names (used in `AbstractEntity::toArray()`)
     *
     * @return string[]
     */
    public static function getAttributeNames(): array
    {
        return array_keys(get_class_vars(__CLASS__));
    }
```

This is simplest implementation. You can make this method more complex to add virtual properties or to remove hidden
properties.

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

    /**
     * Returns list of model's attribute names (used in `AbstractEntity::toArray()`)
     *
     * @return string[]
     */
    public static function getAttributeNames(): array
    {
        return array_keys(get_class_vars(__CLASS__));
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
