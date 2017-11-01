<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests;

use gugglegum\AbstractEntity\Exception;
use gugglegum\AbstractEntity\tests\models\Message;
use gugglegum\AbstractEntity\tests\models\CustomPost;
use gugglegum\AbstractEntity\tests\models\CustomUser;
use PHPUnit\Framework\TestCase;

/**
 * Custom Model Test
 *
 * This is almost a copy of AbstractEntityTest class but it uses `CustomUser` and `CustomPost` instead of `User` and
 * `Post`. We test that all these custom models works absolutely the same as its usual versions. Custom models are using
 * associative array for storing attributes and overrides the `getAttributeNames()` method.
 *
 * @package gugglegum\AbstractEntity\tests
 */
class CustomModelTest extends TestCase
{
    /**
     * Testing constructor. Constructor may be called with associative array with initial model values of attributes or
     * without arguments.
     */
    public function testConstructor()
    {
        /*
         * When creating user model without arguments all attributes except `disabled` must be null. The `disabled`
         * attribute has default initial value `FALSE` defined in the CustomUser class.
         */
        $user = new CustomUser();
        $this->assertNull($user->getName());
        $this->assertNull($user->getEmail());
        $this->assertFalse($user->isDisabled());

        /*
         * Creating a user model with partially defined attributes.
         */
        $user = new CustomUser([
            'name' => 'John',
            'disabled' => true,
        ]);
        $this->assertEquals('John', $user->getName());
        $this->assertNull($user->getEmail());
        $this->assertTrue($user->isDisabled());
    }

    /**
     * By default exception class must be \gugglegum\AbstractEntity\Exception
     */
    public function testGetExceptionClass()
    {
        $user = new CustomUser();
        $this->assertEquals(CustomException::class, $user->__getExceptionClass());

        $post = new CustomPost();
        $this->assertEquals(Exception::class, $post->__getExceptionClass());
    }

    /**
     * By using `__setExceptionClass()` user can redefine exception class.
     */
    public function testSetExceptionClass()
    {
        $user = new CustomUser();
        $post = new CustomPost();
        // Setting standard class for $user model (by default CustomUser uses CustomException)
        $user->__setExceptionClass(Exception::class);
        // Setting CustomException for $post model (by default CustomPost uses Exception)
        $post->__setExceptionClass(CustomException::class);
        // Checking that exception class for $post was changed
        $this->assertEquals(CustomException::class, $post->__getExceptionClass());
        // Checking that exception class for $user was changed
        $this->assertEquals(Exception::class, $user->__getExceptionClass());
    }

    /**
     * Static method ::fromArray() makes the same as `new Model([...])` but via static call. Just check it's working.
     */
    public function testFromArray()
    {
        $user = CustomUser::fromArray([
            'email' => 'john@example.com',
        ]);
        $this->assertNull($user->getName());
        $this->assertEquals('john@example.com', $user->getEmail());
        $this->assertFalse($user->isDisabled());
    }

    /**
     * The method `setFromArray()` allows to set a multiple attributes at once through associative array. It may set
     * all attributes or just part. Values of not mentioned attributes doesn't changes.
     */
    public function testSetFromArray()
    {
        $user = new CustomUser([
            'name' => 'John',
            'email' => 'john@example.com',
            'disabled' => true,
        ]);
        $user->setFromArray([
            'email' => 'john.doe@example.com',
            'disabled' => false,
        ]);
        $this->assertEquals('John', $user->getName());
        $this->assertEquals('john.doe@example.com', $user->getEmail());
        $this->assertFalse($user->isDisabled());
    }

    /**
     * Checks throwing an exception on attempt to set unknown attribute from associative array.
     */
    public function testSetFromArrayUnknownAttribute()
    {
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage('Attempt to set non-existing attribute "email1"');
        new CustomUser([
            'name' => 'John',
            'email1' => 'john@example.com',
            'disabled' => true,
        ]);
    }

    /**
     * The `getAttributeNames()` method returns list of attributes. By default this method returns a list of all
     * non-static properties of model class and all parent classes. But it caches the list using static variable
     * inside method body.
     */
    public function testGetAttributeNames()
    {
        $expectedCustomUserAttributeNames = [
            'name',
            'email',
            'disabled',
        ];

        $expectedMessageAttributeNames = [
            'datetime',
            'userId',
            'text',
        ];

        $expectedCustomPostAttributeNames = [
            'datetime',
            'userId',
            'text',
            'title',
            'labels',
        ];

        $this->assertEquals($expectedCustomUserAttributeNames, CustomUser::getAttributeNames());
        $this->assertEquals($expectedMessageAttributeNames, Message::getAttributeNames());
        $this->assertEquals($expectedCustomPostAttributeNames, CustomPost::getAttributeNames());

        // Test it twice and in reverse order because we use static property to cache list of attribute names
        $this->assertEquals($expectedCustomPostAttributeNames, CustomPost::getAttributeNames());
        $this->assertEquals($expectedMessageAttributeNames, Message::getAttributeNames());
        $this->assertEquals($expectedCustomUserAttributeNames, CustomUser::getAttributeNames());
    }

    /**
     * Checks that `hasAttribute` returns TRUE for every attribute actually existing in testing models and returns FALSE
     * for all attributes existing in other models, i.e. we checking for possible interference of attributes due to
     * use of static property for caching attributes list.
     */
    public function testHasAttribute()
    {
        $existingCustomUserAttributes = [
            'name',
            'email',
            'disabled',
        ];

        $existingMessageAttributes = [
            'datetime',
            'userId',
            'text',
        ];

        // Due to CustomPost model extends Message it inherits its attributes
        $existingCustomPostAttributes = array_merge($existingMessageAttributes, [
            'title',
            'labels',
        ]);

        $nonExistingCustomUserAttributes = $existingCustomPostAttributes;
        $nonExistingMessageAttribute = array_merge($existingCustomUserAttributes, array_diff($existingCustomPostAttributes, $existingMessageAttributes));
        $nonExistingCustomPostAttributes = $existingCustomUserAttributes;

        foreach ($existingCustomUserAttributes as $attributeName) {
            $this->assertEquals(true, CustomUser::hasAttribute($attributeName));
        }
        foreach ($nonExistingCustomUserAttributes as $attributeName) {
            $this->assertEquals(false, CustomUser::hasAttribute($attributeName));
        }

        foreach ($existingMessageAttributes as $attributeName) {
            $this->assertEquals(true, Message::hasAttribute($attributeName));
        }

        foreach ($nonExistingMessageAttribute as $attributeName) {
            $this->assertEquals(false, Message::hasAttribute($attributeName));
        }

        foreach ($existingCustomPostAttributes as $attributeName) {
            $this->assertEquals(true, CustomPost::hasAttribute($attributeName));
        }
        foreach ($nonExistingCustomPostAttributes as $attributeName) {
            $this->assertEquals(false, CustomPost::hasAttribute($attributeName));
        }
    }

    /**
     * Checks that `getAttribute()` works just fine
     */
    public function testGetAttribute()
    {
        $user = new CustomUser([
            'name' => 'John',
            'disabled' => true,
        ]);
        $this->assertEquals('John', $user->getAttribute('name'));
        $this->assertNull($user->getAttribute('email'));
        $this->assertTrue($user->getAttribute('disabled'));
    }

    /**
     * Checks that `setAttribute()` works too
     */
    public function testSetAttribute()
    {
        $user = new CustomUser([
            'name' => 'John',
            'email' => 'john@example.com',
            'disabled' => true,
        ]);
        $user->setAttribute('email', 'john.doe@example.com');
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
        $user = new CustomUser();
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage('Attempt to get non-existing attribute "email1"');
        $user->getAttribute('email1');
    }

    /**
     * Checks that `setAttribute()` throws an exception on attempt to set attribute not existing in the model
     */
    public function testSetUnknownAttribute()
    {
        $user = new CustomUser([
            'name' => 'John',
            'email' => 'john@example.com',
            'disabled' => true,
        ]);

        $this->expectException(CustomException::class);
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
            'disabled' => true,
        ];

        $user = new CustomUser($data);
        $this->assertEquals($data, $user->toArray());
    }

    /**
     * Checks that static methods `getAttributeNames()` and `hasAttribute()` works fine with non-static calls as well.
     */
    public function testStaticMethodsWithNonStaticCalls()
    {
        $user = new CustomUser();
        $this->assertEquals([
            'name',
            'email',
            'disabled',
        ], $user->getAttributeNames());

        $this->assertTrue($user->hasAttribute('email'));
        $this->assertFalse($user->hasAttribute('title'));
        $this->assertFalse($user->hasAttribute('text'));
    }
}
