<?php

namespace Tests\Unit;

require_once "wp-wrapper.php";

use PHPUnit\Framework\TestCase;
use RegisterCustomTypes\CustomPostType;

class CustomPostTypeTest extends TestCase
{
    public function testRegister()
    {
        $customTaxonomy = new CustomPostType('a', 'b', 'c');
        $this->expectOutputString('Added action initRegistered post type a');
        $customTaxonomy->register();
    }
}
