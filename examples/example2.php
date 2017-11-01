<?php

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * Example 2
 *
 * Create empty model instance (without initialization by values) and then set attributes by associative array,
 * by set attribute and by setter methods.
 */

$post = new \gugglegum\AbstractEntity\tests\models\Post();

$post->setFromArray([
    'userId' => 1,
    'title' => 'Hello world',
]);
$post->setAttribute('datetime', new DateTime('now'));
$post->setLabels(['test', 'example']);

var_dump($post->toArray());
