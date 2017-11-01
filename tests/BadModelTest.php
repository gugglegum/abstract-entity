<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests;

use gugglegum\AbstractEntity\Exception;
use gugglegum\AbstractEntity\tests\models\BadModel;
use PHPUnit\Framework\TestCase;

/**
 * This test class tests exceptions that will be raised on non-well formed models, i.e. models without some needle
 * getters or setters. For these test we use special `BadModel` class.
 */
class BadModelTest extends TestCase
{
    /**
     * The `BadModel` doesn't contains getter for `messageId` attribute. So attempt to get this attribute will raise
     * an exception.
     */
    public function testMissingGetter()
    {
        $model = new BadModel([
            'userId' => 1,
            'messageId' => 2,
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Can\'t find getter method getMessageId() or isMessageId() for attribute "messageId" in ' . get_class($model));

        $model->getAttribute('messageId');
    }

    /**
     * The `BadModel` doesn't contains setter for `topicId` attribute.
     */
    public function testMissingSetter()
    {
        $model = new BadModel([
            'userId' => 1,
            'messageId' => 2,
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Can\'t find setter method setTopicId() for attribute "topicId" in ' . get_class($model));

        $model->setAttribute('topicId', 3);
    }

    /**
     * Due to missing getter for `messageId` attribute, the `toArray()` method will not work as well.
     */
    public function testToArray()
    {
        $model = new BadModel([
            'userId' => 1,
            'messageId' => 2,
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Can\'t find getter method getMessageId() or isMessageId() for attribute "messageId" in ' . get_class($model));

        $model->toArray();
    }
}
