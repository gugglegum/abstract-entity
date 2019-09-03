<?php

use gugglegum\AbstractEntity\tests\models\User;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * Example 1
 *
 * The simplest usage: create model instance initializing it via associative array passed to the constructor and then
 * print its contents exported to array.
 */

$user = new User([
    'name' => 'John',
    'email' => 'john@example.com',
    'isAdmin' => false,
    'disabled' => true,
]);

var_dump($user->toArray());
