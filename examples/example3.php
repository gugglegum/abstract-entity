<?php

use gugglegum\AbstractEntity\Exception;
use gugglegum\AbstractEntity\tests\CustomException;
use gugglegum\AbstractEntity\tests\models\CustomPost;
use gugglegum\AbstractEntity\tests\models\CustomUser;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * Example 3
 *
 * Attempt to create CustomUser model instance initializing it by associative array. One of array items contains
 * key that non exists in CustomUser as attribute. This produces CustomException because CustomUser model defines
 * usage of CustomException in its constructor. We catch this exception by try..catch construction.
 *
 * Additionally we instantiating CustomPost model instance. Initializing some values and then we attempt to get
 * unknown attribute. This produces default Exception because CustomPost (unlike CustomUser) doesn't redefines
 * exception class. We catch it too.
 */

try {
    $user = new CustomUser([
        'name' => 'John',
        'email' => 'john@example.com',
        'disabled' => true,
        '@#$^%@#&$&' => 'some unknown attribute',
    ]);
} catch (CustomException $e) {
    echo "Failed to instantiate model of CustomUser class: {$e->getMessage()}\n";
}

$post = new CustomPost();

$post->setFromArray([
    'userId' => 1,
    'title' => 'Hello world',
]);
$post->setAttribute('datetime', new DateTime('now'));
$post->setLabels(['test', 'example']);

try {
    $post->getAttribute('!@%@#^#$^&');
} catch (Exception $e) {
    echo "Operation failed: {$e->getMessage()}\n";
}

var_dump($post->toArray());
