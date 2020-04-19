<?php

namespace Tests\Unit;

require_once "wp-wrapper.php";

use PHPUnit\Framework\TestCase;
use RegisterCustomTypes\CustomTaxonomy;

class CustomTaxonomyTest extends TestCase
{
    public function testRegister()
    {
        $customTaxonomy = new CustomTaxonomy('a', 'b', 'c');
        $this->expectOutputString('Added action initRegistered taxonomy a');
        $customTaxonomy->register();
    }
}
