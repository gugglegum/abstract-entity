<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests;

use gugglegum\AbstractEntity\Exception;
use gugglegum\AbstractEntity\tests\entities\CustomPost;
use gugglegum\AbstractEntity\tests\entities\CustomUser;
use gugglegum\AbstractEntity\tests\entities\DTOUser;
use gugglegum\AbstractEntity\tests\entities\Message;
use gugglegum\AbstractEntity\tests\entities\Post;
use gugglegum\AbstractEntity\tests\entities\User;
use PHPUnit\Framework\TestCase;

/**
 * AbstractEntity Test
 *
 * A main test class for testing AbstractEntity class functionality.
 */
class AbstractEntityTest extends TestCase
{
    /**
     * Testing constructor. Constructor may be called with associative array with initial entity values of attributes or
     * without arguments.
     */
    public function testConstructor()
    {
        foreach ([User::class, CustomUser::class, DTOUser::class] as $userClass) {
            $this->_testConstructor($userClass);
        }
    }
    public function _testConstructor(string $userClass = User::class)
    {
        /*
         * When creating user entity without arguments all attributes except `disabled` must be null. The `disabled`
         * attribute has default initial value `FALSE` defined in the User class.
         */
        /** @var User $user */
        $user = new $userClass();
        $this->assertNull(!$user instanceof DTOUser ? $user->getName() : $user->name);
        $this->assertNull(!$user instanceof DTOUser ? $user->getEmail(): $user->email);
        $this->assertFalse(!$user instanceof DTOUser ? $user->isDisabled() : $user->disabled);
        $this->assertFalse(!$user instanceof DTOUser ? $user->isAdmin() : $user->isAdmin);

        /*
         * Creating a user entity with partially defined attributes.
         */
        /** @var User $user */
        $user = new $userClass([
            'name' => 'John',
            'isAdmin' => true,
            'disabled' => true,
        ]);
        $this->assertEquals('John', !$user instanceof DTOUser ? $user->getName() : $user->name);
        $this->assertNull(!$user instanceof DTOUser ? $user->getEmail() : $user->email);
        $this->assertTrue(!$user instanceof DTOUser ? $user->isAdmin(): $user->isAdmin);
        $this->assertTrue(!$user instanceof DTOUser ? $user->isDisabled() : $user->disabled);
    }

    /**
     * By default, exception class must be \gugglegum\AbstractEntity\Exception
     */
    public function testGetExceptionClass()
    {
        foreach ([User::class, CustomUser::class, DTOUser::class] as $userClass) {
            $this->_testGetExceptionClass($userClass);
        }
    }
    public function _testGetExceptionClass(string $userClass = User::class)
    {
        /** @var User $user */
        $user = new $userClass();
        $this->assertEquals($userClass == CustomUser::class ? CustomException::class : Exception::class, $user->__getExceptionClass());
    }

    /**
     * By using `__setExceptionClass()` user can redefine exception class.
     */
    public function testSetExceptionClass()
    {
        foreach ([User::class, CustomUser::class, DTOUser::class] as $userClass) {
            foreach ([Post::class, CustomPost::class] as $postClass) {
                $this->_testSetExceptionClass($userClass, $postClass);
            }
        }
    }
    public function _testSetExceptionClass(string $userClass = User::class, string $postClass = Post::class)
    {
        /** @var User $user */
        $user = new $userClass();
        /** @var Post $post */
        $post = new $postClass();
        // Setting class for $user entity
        $user->__setExceptionClass(CustomException::class);
        // Checking that exception class for $post not changed
        $this->assertEquals(Exception::class, $post->__getExceptionClass());
        // Checking that exception class for $user was changed
        $this->assertEquals(CustomException::class, $user->__getExceptionClass());
        // Checking again that exception class for $post still not changed (just in case)
        $this->assertEquals(Exception::class, $post->__getExceptionClass());
    }

    /**
     * Static method ::fromArray() makes the same as `new Entity([...])` but via static call. Just check it's working.
     */
    public function testFromArray()
    {
        foreach ([User::class, CustomUser::class, DTOUser::class] as $userClass) {
            $this->_testFromArray($userClass);
        }
    }
    public function _testFromArray(string $userClass = User::class)
    {
        /** @var User $user */
        $user = $userClass::fromArray([
            'email' => 'john@example.com',
            'isAdmin' => true,
        ]);
        $this->assertNull(!$user instanceof DTOUser ? $user->getName() : $user->name);
        $this->assertEquals('john@example.com', !$user instanceof DTOUser ? $user->getEmail() : $user->email);
        $this->assertTrue(!$user instanceof DTOUser ? $user->isAdmin() : $user->isAdmin);
        $this->assertFalse(!$user instanceof DTOUser ? $user->isDisabled() : $user->disabled);
    }

    /**
     * The method `setFromArray()` allows to set a multiple attributes at once through associative array. It may set
     * all attributes or just part. Values of not mentioned attributes doesn't change.
     */
    public function testSetFromArray()
    {
        foreach ([User::class, CustomUser::class, DTOUser::class] as $userClass) {
            $this->_testSetFromArray($userClass);
        }
    }
    public function _testSetFromArray(string $userClass = User::class)
    {
        /** @var User $user */
        $user = new $userClass([
            'name' => 'John',
            'email' => 'john@example.com',
            'isAdmin' => true,
            'disabled' => true,
        ]);
        $user->setFromArray([
            'email' => 'john.doe@example.com',
            'isAdmin' => false,
            'disabled' => false,
        ]);
        $this->assertEquals('John', !$user instanceof DTOUser ? $user->getName() : $user->name);
        $this->assertEquals('john.doe@example.com', !$user instanceof DTOUser ? $user->getEmail() : $user->email);
        $this->assertFalse(!$user instanceof DTOUser ? $user->isAdmin() : $user->isAdmin);
        $this->assertFalse(!$user instanceof DTOUser ? $user->isDisabled() : $user->disabled);
    }

    /**
     * Checks throwing an exception on attempt to set unknown attribute from associative array.
     */
    public function testSetFromArrayUnknownAttribute()
    {
        foreach ([User::class, CustomUser::class, DTOUser::class] as $userClass) {
            $this->_testSetFromArrayUnknownAttribute($userClass);
        }
    }
    public function _testSetFromArrayUnknownAttribute(string $userClass = User::class)
    {
        $this->expectException($userClass == CustomUser::class ? CustomException::class : Exception::class);
        $this->expectExceptionMessage('Attempt to set non-existing attribute "email1"');
        new $userClass([
            'name' => 'John',
            'email1' => 'john@example.com',
            'isAdmin' => true,
            'disabled' => true,
        ]);
    }

    /**
     * The `getAttributeNames()` method returns list of attributes. By default, this method returns a list of all
     * non-static properties of entity class and all parent classes. But it caches the list using static variable
     * inside method body.
     */
    public function testGetAttributeNames()
    {
        foreach ([User::class, CustomUser::class, DTOUser::class] as $userClass) {
            foreach ([Post::class, CustomPost::class] as $postClass) {
                $this->_testGetAttributeNames($userClass, $postClass);
            }
        }
    }
    public function _testGetAttributeNames(string $userClass = User::class, string $postClass = Post::class)
    {
        $expectedUserAttributeNames = [
            'name',
            'email',
            'isAdmin',
            'disabled',
        ];

        $expectedMessageAttributeNames = [
            'datetime',
            'userId',
            'text',
        ];

        $expectedPostAttributeNames = [
            'datetime',
            'userId',
            'text',
            'title',
            'labels',
        ];

        $this->assertEquals($expectedUserAttributeNames, $userClass::getAttributeNames());
        $this->assertEquals($expectedMessageAttributeNames, Message::getAttributeNames());
        $this->assertEquals($expectedPostAttributeNames, $postClass::getAttributeNames());

        // Test it twice and in reverse order because we use static property to cache list of attribute names
        $this->assertEquals($expectedPostAttributeNames, $postClass::getAttributeNames());
        $this->assertEquals($expectedMessageAttributeNames, Message::getAttributeNames());
        $this->assertEquals($expectedUserAttributeNames, $userClass::getAttributeNames());
    }

    /**
     * Checks that `hasAttribute` returns TRUE for every attribute actually existing in testing entities and returns FALSE
     * for all attributes existing in other entities, i.e. we're checking for possible interference of attributes due to
     * use of static property for caching attributes list.
     */
    public function testHasAttribute()
    {
        foreach ([User::class, CustomUser::class, DTOUser::class] as $userClass) {
            foreach ([Post::class, CustomPost::class] as $postClass) {
                $this->_testHasAttribute($userClass, $postClass);
            }
        }
    }
    public function _testHasAttribute(string $userClass = User::class, string $postClass = Post::class)
    {
        $existingUserAttributes = [
            'name',
            'email',
            'isAdmin',
            'disabled',
        ];

        $existingMessageAttributes = [
            'datetime',
            'userId',
            'text',
        ];

        // Due to Post entity extends Message it inherits its attributes
        $existingPostAttributes = array_merge($existingMessageAttributes, [
            'title',
            'labels',
        ]);

        $nonExistingUserAttributes = $existingPostAttributes;
        $nonExistingMessageAttribute = array_merge($existingUserAttributes, array_diff($existingPostAttributes, $existingMessageAttributes));
        $nonExistingPostAttributes = $existingUserAttributes;

        foreach ($existingUserAttributes as $attributeName) {
            $this->assertEquals(true, $userClass::hasAttribute($attributeName));
        }
        foreach ($nonExistingUserAttributes as $attributeName) {
            $this->assertEquals(false, $userClass::hasAttribute($attributeName));
        }

        foreach ($existingMessageAttributes as $attributeName) {
            $this->assertEquals(true, Message::hasAttribute($attributeName));
        }

        foreach ($nonExistingMessageAttribute as $attributeName) {
            $this->assertEquals(false, Message::hasAttribute($attributeName));
        }

        foreach ($existingPostAttributes as $attributeName) {
            $this->assertEquals(true, $postClass::hasAttribute($attributeName));
        }
        foreach ($nonExistingPostAttributes as $attributeName) {
            $this->assertEquals(false, $postClass::hasAttribute($attributeName));
        }
    }

    /**
     * Checks that `getAttribute()` works just fine
     */
    public function testGetAttribute()
    {
        foreach ([User::class, CustomUser::class, DTOUser::class] as $userClass) {
            $this->_testGetAttribute($userClass);
        }
    }
    public function _testGetAttribute(string $userClass = User::class)
    {
        /** @var User $user */
        $user = new $userClass([
            'name' => 'John',
            'disabled' => true,
        ]);
        $this->assertEquals('John', $user->getAttribute('name'));
        $this->assertNull($user->getAttribute('email'));
        $this->assertTrue($user->getAttribute('disabled'));
        $this->assertFalse($user->getAttribute('isAdmin'));
    }

    /**
     * Checks that `setAttribute()` works too
     */
    public function testSetAttribute()
    {
        foreach ([User::class, CustomUser::class, DTOUser::class] as $userClass) {
            $this->_testSetAttribute($userClass);
        }
    }
    public function _testSetAttribute(string $userClass = User::class)
    {
        /** @var User $user */
        $user = new $userClass([
            'name' => 'John',
            'email' => 'john@example.com',
            'isAdmin' => true,
            'disabled' => true,
        ]);
        $user->setAttribute('email', 'john.doe@example.com');
        $user->setAttribute('isAdmin', false);
        $user->setAttribute('disabled', false);

        $this->assertEquals('John', !$user instanceof DTOUser ? $user->getName() : $user->name);
        $this->assertEquals('john.doe@example.com', !$user instanceof DTOUser ? $user->getEmail() : $user->email);
        $this->assertFalse(!$user instanceof DTOUser ? $user->isDisabled() : $user->disabled);
    }

    public function testSetParentAttribute()
    {
        foreach ([Post::class, CustomPost::class] as $postClass) {
            $this->_testSetParentAttribute($postClass);
        }
    }
    public function _testSetParentAttribute(string $postClass = Post::class)
    {
        /** @var Post $post */
        $post = new $postClass();
        $post->setFromArray([
            'userId' => 1,
            'title' => 'Hello world',
        ]);
        $post->setAttribute('datetime', new \DateTime('now'));
        $this->assertNotEmpty($post->getAttribute('datetime'));
    }

    /**
     * Checks that `getAttribute()` throws an exception on attempt to get attribute not existing in the entity
     */
    public function testGetUnknownAttribute()
    {
        foreach ([User::class, CustomUser::class, DTOUser::class] as $userClass) {
            $this->_testGetUnknownAttribute($userClass);
        }
    }
    public function _testGetUnknownAttribute(string $userClass = User::class)
    {
        /** @var User $user */
        $user = new $userClass();
        $this->expectException($userClass == CustomUser::class ? CustomException::class : Exception::class);
        $this->expectExceptionMessage('Attempt to get non-existing attribute "email1"');
        $user->getAttribute('email1');
    }

    /**
     * Checks that `setAttribute()` throws an exception on attempt to set attribute not existing in the entity
     */
    public function testSetUnknownAttribute()
    {
        foreach ([User::class, CustomUser::class, DTOUser::class] as $userClass) {
            $this->_testSetUnknownAttribute($userClass);
        }
    }
    public function _testSetUnknownAttribute(string $userClass = User::class)
    {
        /** @var User $user */
        $user = new $userClass();
        $this->expectException($userClass == CustomUser::class ? CustomException::class : Exception::class);
        $this->expectExceptionMessage('Attempt to set non-existing attribute "email1"');
        $user->setAttribute('email1', 'john.doe@example.com');
    }

    /**
     * Checks that `toArray()` method works correctly and that associative array passed in the constructor equals array
     * returning by `toArray()`.
     */
    public function testToArray()
    {
        foreach ([User::class, CustomUser::class, DTOUser::class] as $userClass) {
            $this->_testToArray($userClass);
        }
    }
    public function _testToArray(string $userClass = User::class)
    {
        $data = [
            'name' => 'John',
            'email' => 'john@example.com',
            'isAdmin' => false,
            'disabled' => true,
        ];
        /** @var User $user */
        $user = new $userClass($data);
        $this->assertEquals($data, $user->toArray());
    }

    public function testToArrayWithParent()
    {
        foreach ([Post::class, CustomPost::class] as $postClass) {
            $this->_testToArrayWithParent($postClass);
        }
    }
    public function _testToArrayWithParent(string $postClass = Post::class)
    {
        /** @var Post $post */
        $post = new $postClass();
        $dt = new \DateTime('now');
        $post->setFromArray([
            'userId' => 1,
            'title' => 'Hello world',
            'datetime' => $dt,
        ]);
        $this->assertEquals([
            'userId' => 1,
            'title' => 'Hello world',
            'datetime' => $dt,
            'labels' => [],
        ], $post->toArray());
    }

    /**
     * Checks that static methods `getAttributeNames()` and `hasAttribute()` works fine with non-static calls as well.
     */
    public function testStaticMethodsWithNonStaticCalls()
    {
        foreach ([User::class, CustomUser::class, DTOUser::class] as $userClass) {
            $this->_testStaticMethodsWithNonStaticCalls($userClass);
        }
    }
    public function _testStaticMethodsWithNonStaticCalls(string $userClass = User::class)
    {
        /** @var User $user */
        $user = new $userClass();
        $this->assertEquals([
            'name',
            'email',
            'isAdmin',
            'disabled',
        ], $user->getAttributeNames());

        $this->assertTrue($user->hasAttribute('email'));
        $this->assertFalse($user->hasAttribute('title'));
        $this->assertFalse($user->hasAttribute('text'));
    }

    public function testJsonSerialize()
    {
        foreach ([User::class, CustomUser::class, DTOUser::class] as $userClass) {
            $this->_testJsonSerialize($userClass);
        }
    }
    public function _testJsonSerialize(string $userClass = User::class)
    {
        $data = [
            'name' => 'John',
            'email' => 'john@example.com',
            'isAdmin' => false,
            'disabled' => true,
        ];
        /** @var User $user */
        $user = $userClass::fromArray($data);
        $this->assertEquals(json_encode($data), json_encode($user));
    }

}
