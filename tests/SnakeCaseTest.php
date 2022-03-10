<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests;

use gugglegum\AbstractEntity\tests\entities\SnakeCaseEntity;
use PHPUnit\Framework\TestCase;

class SnakeCaseTest extends TestCase
{
    public function testSnakeCaseEntity()
    {
        $entity = new SnakeCaseEntity();
        $entity->setUserName('John')
            ->setIsDeleted(true)
            ->setIsEmailConfirmed(true)
            ->setBadLoginCounter(5);

        $this->assertEquals('John', $entity->getUserName());
        $this->assertEquals(true, $entity->isIsDeleted());
        $this->assertEquals(true, $entity->isEmailConfirmed());
        $this->assertEquals(5, $entity->getBadLoginCounter());

        $this->assertEquals([
            'user_name' => 'John',
            'is_deleted' => true,
            'is_email_confirmed' => true,
            'bad_login_counter' => 5,
        ], $entity->toArray());

        $entity->setOptionalParameter('123');

        $this->assertEquals([
            'user_name' => 'John',
            'is_deleted' => true,
            'is_email_confirmed' => true,
            'bad_login_counter' => 5,
            'optional_parameter' => '123',
        ], $entity->toArray());
    }
}
