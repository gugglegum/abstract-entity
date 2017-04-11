<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/User.php';

$user = User::fromArray([
    'name' => 'John Smith',
    'email' => 'john.smith@example.com',
    'disabled' => true,
]);

var_dump($user->toArray());

