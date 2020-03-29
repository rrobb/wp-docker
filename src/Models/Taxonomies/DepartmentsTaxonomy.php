<?php
declare(strict_types=1);

namespace WPSite\Models\Taxonomies;

use WPSite\Models\GenericTaxonomy;

/**
 * Class ExperiencesTaxonomy
 * @package WPSite\Models\Taxonomies
 */
class DepartmentsTaxonomy extends GenericTaxonomy
{
    public const TYPE = 'departments';

    /**
     * Returns the taxonomy type
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * Returns the taxonomy name in the plural form
     * @return string
     */
    public function getPlural(): string
    {
        return 'Departments';
    }

    /**
     * Returns the taxonomy name in the plural form
     * @return string
     */
    public function getSingle(): string
    {
        return 'Department';
    }
}
