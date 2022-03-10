<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests;

use gugglegum\AbstractEntity\ArrayableInterface;
use gugglegum\AbstractEntity\tests\entities\GroupsOfUsers;
use gugglegum\AbstractEntity\tests\entities\ListOfUsers;
use gugglegum\AbstractEntity\tests\entities\Message;
use gugglegum\AbstractEntity\tests\entities\Post;
use gugglegum\AbstractEntity\tests\entities\SuperEntity;
use gugglegum\AbstractEntity\tests\entities\SuperEntityWithHandler1;
use gugglegum\AbstractEntity\tests\entities\SuperEntityWithHandler2;
use gugglegum\AbstractEntity\tests\entities\SuperEntityWithHandler3;
use gugglegum\AbstractEntity\tests\entities\SuperEntityWithHandler4;
use gugglegum\AbstractEntity\tests\entities\User;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;

class SuperEntityTest extends TestCase
{
    public function testToArrayEntityWithEntities()
    {
        $entity = new SuperEntity();
        $dt = new \DateTime('now');
        $expectedData = $this->composeEntity($entity, $dt);

        $this->assertEquals($expectedData, $entity->toArray());

        // toArray object handler extended by conversion of stdClass objects, but not DateTime (with recursion on ArrayableInterface)
        $expectedData['simpleObject'] = [
            'counter' => 5,
            'lastUpdate' => $dt,
            'innerObject' => $expectedData['simpleObject']->innerObject,
        ];
        $handler = function(object $obj) use (&$handler) {
            if ($obj instanceof ArrayableInterface) {
                return $obj->toArray($handler);
            }
            if ($obj instanceof \stdClass) {
                return get_object_vars($obj);
            }
            return $obj;
        };
        $this->assertEquals($expectedData, $entity->toArray($handler));

        // toArray object handler extended by conversion of both stdClass and DateTime objects (without recursion)
        $dtFormatted = $dt->format('c');
        $expectedData['dateTime'] = $dtFormatted;
        $handler = function(object $obj) use (&$handler) {
            if ($obj instanceof ArrayableInterface) {
                return $obj->toArray(); // <--- missing toArray() without $handler makes handler non-recursive
            }
            if ($obj instanceof \stdClass) {
                return get_object_vars($obj);
            }
            if ($obj instanceof \DateTime) {
                return $obj->format('c');
            }
            return $obj;
        };
        $this->assertEquals($expectedData, $entity->toArray($handler));

        // toArray object handler extended by conversion of both stdClass and DateTime objects (with recursion on ArrayableInterface)
        $expectedData['message']['datetime'] = $dtFormatted;
        $expectedData['post']['datetime'] = $dtFormatted;
        $handler = function(object $obj) use (&$handler) {
            if ($obj instanceof ArrayableInterface) {
                return $obj->toArray($handler);
            }
            if ($obj instanceof \stdClass) {
                return get_object_vars($obj);
            }
            if ($obj instanceof \DateTime) {
                return $obj->format('c');
            }
            return $obj;
        };
        $this->assertEquals($expectedData, $entity->toArray($handler));

        // toArray object handler extended by conversion of both stdClass and DateTime objects (with recursion on ArrayableInterface and stdClass)
        $expectedData['message']['datetime'] = $dtFormatted;
        $expectedData['post']['datetime'] = $dtFormatted;
        $expectedData['simpleObject']['lastUpdate'] = $dtFormatted;
        $expectedData['simpleObject']['innerObject'] = ['createdAt' => $dtFormatted];
        $handler = function(object $obj) use (&$handler) {
            if ($obj instanceof ArrayableInterface) {
                return $obj->toArray($handler); // <-- recursive toArray object handler for ArrayableInterface
            }
            if ($obj instanceof \stdClass) {
                return array_map(function($value) use ($handler) {
                    return is_object($value)
                        ? $handler($value)  // <-- recursive toArray object handler for \stdClass
                        : $value;
                }, get_object_vars($obj));
            }
            if ($obj instanceof \DateTime) {
                return $obj->format('c');
            }
            return $obj;
        };
        $this->assertEquals($expectedData, $entity->toArray($handler));
    }

    #[ArrayShape(['message' => "array", 'post' => "array", 'listOfUsers' => "array[]", 'groupsOfUsers' => "\array[][]", 'simpleObject' => "\stdClass", 'dateTime' => "\DateTime"])]
    private function composeEntity(SuperEntity $entity, \DateTime $dt): array
    {
        $entity->setMessage((new Message())
            ->setDatetime($dt)
            ->setUserId(1)
            ->setText('Hello')
        );

        $entity->setPost((new Post())
            ->setDatetime($dt)
            ->setUserId(1)
            ->setText('Hello')
            ->setTitle('Hi')
            ->setLabels(['greetings', 'welcome', 'salutation'])
        );

        $entity->setListOfUsers((new ListOfUsers())
            ->addUser((new User())
                ->setName('joe')
                ->setEmail('joe@example.com')
                ->setIsAdmin(false)
                ->setDisabled(false)
            )
            ->addUser(User::fromArray([
                'name' => 'jane',
                'email' => 'jane@example.com',
                'disabled' => true,
            ])->setIsAdmin(true))
        );

        $entity->setGroupsOfUsers((new GroupsOfUsers())
            ->addUserToGroup((new User())->setFromArray(['name' => 'joe'])->setDisabled(false), 'men')
            ->addUserToGroup((new User())->setFromArray(['name' => 'jane'])->setEmail('jane@example.com'), 'women')
        );

        $simpleObject = new \stdClass();
        $simpleObject->counter = 5;
        $simpleObject->lastUpdate = $dt;
        $simpleObject->innerObject = new \stdClass();
        $simpleObject->innerObject->createdAt = $dt;
        $entity->setSimpleObject($simpleObject);

        $entity->setDateTime($dt);

        return [
            'message' =>
                [
                    'datetime' => $dt,
                    'userId' => 1,
                    'text' => 'Hello',
                ],
            'post' =>
                [
                    'datetime' => $dt,
                    'userId' => 1,
                    'text' => 'Hello',
                    'title' => 'Hi',
                    'labels' => [
                        'greetings',
                        'welcome',
                        'salutation',
                    ],
                ],
            'listOfUsers' =>
                [
                    [
                        'name' => 'joe',
                        'email' => 'joe@example.com',
                        'isAdmin' => false,
                        'disabled' => false,
                    ],
                    [
                        'name' => 'jane',
                        'email' => 'jane@example.com',
                        'isAdmin' => true,
                        'disabled' => true,
                    ],
                ],
            'groupsOfUsers' => [
                'men' => [
                    [
                        'name' => 'joe',
                        'email' => NULL,
                        'isAdmin' => false,
                        'disabled' => false,
                    ],
                ],
                'women' => [
                    [
                        'name' => 'jane',
                        'email' => 'jane@example.com',
                        'isAdmin' => false,
                        'disabled' => false,
                    ],
                ],
            ],
            'simpleObject' => $simpleObject,
            'dateTime' => $dt,
        ];
    }


    public function testToArrayWithEntityHandler()
    {
        $entity = new SuperEntityWithHandler1();
        $dt = new \DateTime('now');
        $expectedData = $this->composeEntity($entity, $dt);

        // toArray object handler extended by conversion of stdClass objects, but not DateTime (with recursion on ArrayableInterface)
        $expectedData['simpleObject'] = [
            'counter' => 5,
            'lastUpdate' => $dt,
            'innerObject' => $expectedData['simpleObject']->innerObject,
        ];
        $this->assertEquals($expectedData, $entity->toArray());

        // toArray object handler extended by conversion of both stdClass and DateTime objects (without recursion)
        $entity = new SuperEntityWithHandler2();
        $this->composeEntity($entity, $dt);
        $dtFormatted = $dt->format('c');
        $expectedData['dateTime'] = $dtFormatted;
        $this->assertEquals($expectedData, $entity->toArray());

        // toArray object handler extended by conversion of both stdClass and DateTime objects (with recursion on ArrayableInterface)
        $entity = new SuperEntityWithHandler3();
        $this->composeEntity($entity, $dt);
        $dtFormatted = $dt->format('c');
        $expectedData['message']['datetime'] = $dtFormatted;
        $expectedData['post']['datetime'] = $dtFormatted;
        $this->assertEquals($expectedData, $entity->toArray());

        // toArray object handler extended by conversion of both stdClass and DateTime objects (with recursion on ArrayableInterface and stdClass)
        $entity = new SuperEntityWithHandler4();
        $this->composeEntity($entity, $dt);
        $expectedData['message']['datetime'] = $dtFormatted;
        $expectedData['post']['datetime'] = $dtFormatted;
        $expectedData['simpleObject']['lastUpdate'] = $dtFormatted;
        $expectedData['simpleObject']['innerObject'] = ['createdAt' => $dtFormatted];
        $this->assertEquals($expectedData, $entity->toArray());
    }
}
