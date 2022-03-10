<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests;

use gugglegum\AbstractEntity\Exception;
use gugglegum\AbstractEntity\tests\entities\BadEntity;
use PHPUnit\Framework\TestCase;

/**
 * This test class tests exceptions that will be raised on non-well formed entities, i.e. entities without some needle
 * getters or setters. For these test we use special `BadEntity` class.
 */
class BadEntityTest extends TestCase
{
    /**
     * The `BadEntity` doesn't contain getter for `messageId` attribute. So attempt to get this attribute will raise
     * an exception.
     */
    public function testMissingGetter()
    {
        $entity = new BadEntity([
            'userId' => 1,
            'messageId' => 2,
        ]);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Can\'t find getter method getMessageId() or isMessageId() for attribute "messageId" in ' . get_class($entity));
        $entity->getAttribute('messageId');
    }

    /**
     * The `BadEntity` doesn't contain getter for `isProduction` attribute. So attempt to get this attribute will raise
     * an exception.
     */
    public function testMissingGetter2()
    {
        $entity = new BadEntity();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Can\'t find getter method getIsProduction() or isIsProduction() or isProduction() for attribute "isProduction" in ' . get_class($entity));
        $entity->getAttribute('isProduction');
    }

    /**
     * The `BadEntity` doesn't contain setter for `topicId` attribute.
     */
    public function testMissingSetter()
    {
        $entity = new BadEntity([
            'userId' => 1,
            'messageId' => 2,
        ]);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Can\'t find setter method setTopicId() for attribute "topicId" in ' . get_class($entity));
        $entity->setAttribute('topicId', 3);
    }

    /**
     * Due to missing getter for `messageId` attribute, the `toArray()` method will not work as well.
     */
    public function testToArray()
    {
        $entity = new BadEntity([
            'userId' => 1,
            'messageId' => 2,
        ]);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Can\'t find getter method getMessageId() or isMessageId() for attribute "messageId" in ' . get_class($entity));
        $entity->toArray();
    }
}
