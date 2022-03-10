<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests;

use gugglegum\AbstractEntity\tests\entities\ListOfUsers;
use gugglegum\AbstractEntity\tests\entities\User;
use PHPUnit\Framework\TestCase;

class ListEntityTest extends TestCase
{
    public function testToArray()
    {
        $data1 = [
            'name' => 'John',
            'email' => 'john@example.com',
            'isAdmin' => false,
            'disabled' => true,
        ];
        $data2 = [
            'name' => 'Jane',
            'email' => 'jane@example.com',
            'isAdmin' => true,
            'disabled' => false,
        ];

        $list = new ListOfUsers();
        $list->addUser(User::fromArray($data1));
        $list->addUser(User::fromArray($data2));

        $this->assertEquals([
            [
                'name' => 'John',
                'email' => 'john@example.com',
                'isAdmin' => false,
                'disabled' => true,
            ],
            [
                'name' => 'Jane',
                'email' => 'jane@example.com',
                'isAdmin' => true,
                'disabled' => false,
            ],
        ], $list->toArray());
    }

}
