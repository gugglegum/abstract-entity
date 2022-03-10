<?php

declare(strict_types=1);

namespace gugglegum\AbstractEntity\tests;

use gugglegum\AbstractEntity\tests\entities\OptionalEntity;
use PHPUnit\Framework\TestCase;

class OptionalTest extends TestCase
{
    public function testOptionalEntity()
    {
        $entity = new OptionalEntity();
        $this->assertEquals([
            'param2' => '123',
            'param3' => null,
            'param4' => null,
            'param5' => null,
        ], $entity->toArray());

        $entity->setAttribute('param1', 'asdf');
        $entity->unsetAttribute('param4');
        $this->assertEquals([
            'param1' => 'asdf',
            'param2' => '123',
            'param3' => null,
            'param5' => null,
        ], $entity->toArray());
    }
}
