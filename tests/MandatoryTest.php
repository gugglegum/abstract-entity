<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests;

use gugglegum\AbstractEntity\tests\entities\MandatoryEntity;
use gugglegum\AbstractEntity\tests\entities\Post;
use PHPUnit\Framework\TestCase;

class MandatoryTest extends TestCase
{
    /** Ensure that usual entity without mandatory attributes is consistent by default */
    public function testNotMandatoryEntity()
    {
        $entity = new Post();
        $this->assertTrue($entity->isConsistent());
    }

    /** Ensure that entity with mandatory attributes is inconsistent by default, it has 2 mandatory attributes */
    public function testEmptyMandatoryEntity()
    {
        $entity = new MandatoryEntity();
        $this->assertFalse($entity->isConsistent());
        $this->expectError();
        $entity->toArray();
    }

    /** Fill one of two mandatory attributes, ensure that entity still inconsistent */
    public function testHalfFilledMandatoryEntity()
    {
        $entity = new MandatoryEntity();
        $entity->setAttribute('login', 'john');
        $this->assertFalse($entity->isConsistent());
        $this->expectError();
        $entity->getAttributes();
    }

    /** Fill the both mandatory attribute, ensure that now entity is consistent */
    public function testFullyFilledMandatoryEntity()
    {
        $entity = new MandatoryEntity();
        $this->assertFalse($entity->isConsistent());
        $entity->setAttribute('login', 'john');
        $entity->setAttribute('password', '123*456');
        $this->assertTrue($entity->isConsistent());
        $entity->toArray();
    }
}
