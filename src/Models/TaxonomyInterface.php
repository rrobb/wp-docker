<?php
declare(strict_types=1);

namespace WPSite\Models;

/**
 * Interface TaxonomyInterface
 * @author Rob Burgers <robburgers@gmail.com>
 * @package WPSite\Models
 * @date 22/03/2020
 */
interface TaxonomyInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * returns the array for the registration of the taxonomy
     *
     * @return array
     */
    public function getArgs(): array;

    /**
     * Returns an ordered array of taxonomy terms
     * @return array
     */
    public function getOrderedTerms(): array;
}
