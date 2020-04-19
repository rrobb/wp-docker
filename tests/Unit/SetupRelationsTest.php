<?php

namespace Tests\Unit;

require_once "wp-wrapper.php";

use PHPUnit\Framework\TestCase;
use RegisterCustomTypes\SetupRelations;
use stdClass;

class SetupRelationsTest extends TestCase
{
    public function testRelationsOptionsPage()
    {
    }

    public function testDisplayTaxonomySection()
    {
    }

    public function testWhitelistCustomOptionsPage()
    {
    }

    public function testRegisterRelations()
    {
        $setupRelations = new class extends SetupRelations {
            protected $taxonomies;

            /** @noinspection PhpMissingParentConstructorInspection */
            public function __construct()
            {
                $tax = new stdClass();
                $tax->name = 'taxonomy';

                $this->taxonomies = [$tax];
            }
        };
        $this->expectOutputString('register taxonomy for posttype');
        $setupRelations->registerRelations();
    }

    public function testHandleRelationsOptionsPage()
    {
    }

    public function testCollectTypes()
    {
        $setupRelations = new class extends SetupRelations {
            public function getTaxonomies()
            {
                return $this->taxonomies;
            }

            public function getPostTypes()
            {
                return $this->postTypes;
            }
        };
        $setupRelations->collectTypes();

        $this->assertArrayHasKey('tax', $setupRelations->getTaxonomies());
        $this->assertArrayHasKey('post', $setupRelations->getPostTypes());
    }

    public function testRelationsSettingsInit()
    {
    }

    public function testDisplayPostField()
    {
    }
}
