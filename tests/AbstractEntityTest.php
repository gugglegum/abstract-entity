<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests;

use gugglegum\AbstractEntity\Exception;
use gugglegum\AbstractEntity\tests\models\Message;
use gugglegum\AbstractEntity\tests\models\Post;
use gugglegum\AbstractEntity\tests\models\User;
use PHPUnit\Framework\TestCase;

/**
 * AbstractEntity Test
 *
 * A main test class for testing AbstractEntity class functionality.
 *
 * @package gugglegum\AbstractEntity\tests
 */
class AbstractEntityTest extends TestCase
{
    /**
     * Testing constructor. Constructor may be called with associative array with initial model values of attributes or
     * without arguments.
     */
    public function testConstructor()
    {
        /*
         * When creating user model without arguments all attributes except `disabled` must be null. The `disabled`
         * attribute has default initial value `FALSE` defined in the User class.
         */
        $user = new User();
        $this->assertNull($user->getName());
        $this->assertNull($user->getEmail());
        $this->assertFalse($user->isDisabled());
        $this->assertFalse($user->isAdmin());

        /*
         * Creating a user model with partially defined attributes.
         */
        $user = new User([
            'name' => 'John',
            'isAdmin' => true,
            'disabled' => true,
        ]);
        $this->assertEquals('John', $user->getName());
        $this->assertNull($user->getEmail());
        $this->assertTrue($user->isAdmin());
        $this->assertTrue($user->isDisabled());
    }

    /**
     * By default exception class must be \gugglegum\AbstractEntity\Exception
     */
    public function testGetExceptionClass()
    {
        $user = new User();
        $this->assertEquals(Exception::class, $user->__getExceptionClass());
    }

    /**
     * By using `__setExceptionClass()` user can redefine exception class.
     */
    public function testSetExceptionClass()
    {
        $user = new User();
        $post = new Post();
        // Setting class for $user model
        $user->__setExceptionClass(CustomException::class);
        // Checking that exception class for $post not changed
        $this->assertEquals(Exception::class, $post->__getExceptionClass());
        // Checking that exception class for $user was changed
        $this->assertEquals(CustomException::class, $user->__getExceptionClass());
        // Checking again that exception class for $post still not changed (just in case)
        $this->assertEquals(Exception::class, $post->__getExceptionClass());
    }

    /**
     * Static method ::fromArray() makes the same as `new Model([...])` but via static call. Just check it's working.
     */
    public function testFromArray()
    {
        $user = User::fromArray([
            'email' => 'john@example.com',
            'isAdmin' => true,
        ]);
        $this->assertNull($user->getName());
        $this->assertEquals('john@example.com', $user->getEmail());
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isDisabled());
    }

    /**
     * The method `setFromArray()` allows to set a multiple attributes at once through associative array. It may set
     * all attributes or just part. Values of not mentioned attributes doesn't changes.
     */
    public function testSetFromArray()
    {
        $user = new User([
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
        $this->assertEquals('John', $user->getName());
        $this->assertEquals('john.doe@example.com', $user->getEmail());
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isDisabled());
    }

    /**
     * Checks throwing an exception on attempt to set unknown attribute from associative array.
     */
    public function testSetFromArrayUnknownAttribute()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Attempt to set non-existing attribute "email1"');
        new User([
            'name' => 'John',
            'email1' => 'john@example.com',
            'isAdmin' => true,
            'disabled' => true,
        ]);
    }

//    public function test

    /**
     * The `getAttributeNames()` method returns list of attributes. By default this method returns a list of all
     * non-static properties of model class and all parent classes. But it caches the list using static variable
     * inside method body.
     */
    public function testGetAttributeNames()
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

        $this->assertEquals($expectedUserAttributeNames, User::getAttributeNames());
        $this->assertEquals($expectedMessageAttributeNames, Message::getAttributeNames());
        $this->assertEquals($expectedPostAttributeNames, Post::getAttributeNames());

        // Test it twice and in reverse order because we use static property to cache list of attribute names
        $this->assertEquals($expectedPostAttributeNames, Post::getAttributeNames());
        $this->assertEquals($expectedMessageAttributeNames, Message::getAttributeNames());
        $this->assertEquals($expectedUserAttributeNames, User::getAttributeNames());
    }

    /**
     * Checks that `hasAttribute` returns TRUE for every attribute actually existing in testing models and returns FALSE
     * for all attributes existing in other models, i.e. we checking for possible interference of attributes due to
     * use of static property for caching attributes list.
     */
    public function testHasAttribute()
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

        // Due to Post model extends Message it inherits its attributes
        $existingPostAttributes = array_merge($existingMessageAttributes, [
            'title',
            'labels',
        ]);

        $nonExistingUserAttributes = $existingPostAttributes;
        $nonExistingMessageAttribute = array_merge($existingUserAttributes, array_diff($existingPostAttributes, $existingMessageAttributes));
        $nonExistingPostAttributes = $existingUserAttributes;

        foreach ($existingUserAttributes as $attributeName) {
            $this->assertEquals(true, User::hasAttribute($attributeName));
        }
        foreach ($nonExistingUserAttributes as $attributeName) {
            $this->assertEquals(false, User::hasAttribute($attributeName));
        }

        foreach ($existingMessageAttributes as $attributeName) {
            $this->assertEquals(true, Message::hasAttribute($attributeName));
        }

        foreach ($nonExistingMessageAttribute as $attributeName) {
            $this->assertEquals(false, Message::hasAttribute($attributeName));
        }

        foreach ($existingPostAttributes as $attributeName) {
            $this->assertEquals(true, Post::hasAttribute($attributeName));
        }
        foreach ($nonExistingPostAttributes as $attributeName) {
            $this->assertEquals(false, Post::hasAttribute($attributeName));
        }
    }

    /**
     * Checks that `getAttribute()` works just fine
     */
    public function testGetAttribute()
    {
        $user = new User([
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
        $user = new User([
            'name' => 'John',
            'email' => 'john@example.com',
            'isAdmin' => true,
            'disabled' => true,
        ]);
        $user->setAttribute('email', 'john.doe@example.com');
        $user->setAttribute('isAdmin', false);
        $user->setAttribute('disabled', false);

        $this->assertEquals('John', $user->getName());
        $this->assertEquals('john.doe@example.com', $user->getEmail());
        $this->assertFalse($user->isDisabled());
    }

    /**
     * Checks that `getAttribute()` throws an exception on attempt to get attribute not existing in the model
     */
    public function testGetUnknownAttribute()
    {
        $user = new User();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Attempt to get non-existing attribute "email1"');
        $user->getAttribute('email1');
    }

    /**
     * Checks that `setAttribute()` throws an exception on attempt to set attribute not existing in the model
     */
    public function testSetUnknownAttribute()
    {
        $user = new User([
            'name' => 'John',
            'email' => 'john@example.com',
            'disabled' => true,
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Attempt to set non-existing attribute "email1"');

        $user->setAttribute('email1', 'john.doe@example.com');
    }

    /**
     * Checks that `toArray()` method works correctly and that associative array passed in the constructor equals array
     * returning by `toArray()`.
     */
    public function testToArray()
    {
        $data = [
            'name' => 'John',
            'email' => 'john@example.com',
            'isAdmin' => false,
            'disabled' => true,
        ];

        $user = new User($data);
        $this->assertEquals($data, $user->toArray());
    }

    /**
     * Checks that static methods `getAttributeNames()` and `hasAttribute()` works fine with non-static calls as well.
     */
    public function testStaticMethodsWithNonStaticCalls()
    {
        $user = new User();
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
}
