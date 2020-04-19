<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use RegisterCustomTypes\CustomType;

class CustomTypeTest extends TestCase
{
    public function testAddArgs()
    {
        $customType = new class('a', 'b', 'c') extends CustomType {
            public function register()
            {
            }

            public function getArgs()
            {
                return $this->args;
            }
        };
        $a = $customType->addArgs(['arg1']);
        $b = $customType->addArgs(['arg2']);

        $this->assertInstanceOf(CustomType::class, $a);
        $this->assertInstanceOf(CustomType::class, $b);
        $this->assertEquals(['arg1', 'arg2'], $customType->getArgs());
    }

    public function testSetArgs()
    {
        $customType = new class('a', 'b', 'c') extends CustomType {
            public function register()
            {
            }

            public function getArgs()
            {
                return $this->args;
            }
        };
        $a = $customType->setArgs(['arg1']);
        $b = $customType->setArgs(['arg2']);

        $this->assertInstanceOf(CustomType::class, $a);
        $this->assertInstanceOf(CustomType::class, $b);
        $this->assertEquals(['arg2'], $customType->getArgs());
    }
}
