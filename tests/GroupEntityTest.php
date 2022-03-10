<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests;

use gugglegum\AbstractEntity\tests\entities\GroupsOfUsers;
use gugglegum\AbstractEntity\tests\entities\User;
use PHPUnit\Framework\TestCase;

class GroupEntityTest extends TestCase
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

        $list = new GroupsOfUsers();
        $list->addUserToGroup(User::fromArray($data1), 'men');
        $list->addUserToGroup(User::fromArray($data2), 'women');

        $this->assertEquals([
            'men' => [
                [
                    'name' => 'John',
                    'email' => 'john@example.com',
                    'isAdmin' => false,
                    'disabled' => true,
                ],
            ],
            'women' => [
                [
                    'name' => 'Jane',
                    'email' => 'jane@example.com',
                    'isAdmin' => true,
                    'disabled' => false,
                ],
            ],
        ], $list->toArray());
    }
}
