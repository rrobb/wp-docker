<?php
declare(strict_types=1);

namespace WPSite\Models;

/**
 * Class GenericTaxonomy
 *
 * Creates a taxonomy based on generic rules
 *
 * @package Endouble\Models
 */
abstract class GenericTaxonomy implements TaxonomyInterface
{
    /**
     * The taxonomy name in the plural form
     *
     * @return string
     */
    abstract public function getPlural(): string;

    /**
     * The taxonomy name in the single form. Can be overwritten
     *
     * @return string
     */
    public function getSingle(): string
    {
        return $this->getType();
    }

    /**
     * Arguments for the wp function register_taxonomy
     *
     * @return array
     */
    public function getArgs(): array
    {
        $plural = $this->getPlural();
        $single = $this->getSingle();
        $new = 'Nieuwe ' . strtolower($single);

        return [
            'hierarchical' => true,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => [
                'slug' => $single,
            ],
            'labels' => [
                'name' => $single,
                'singular_name' => $this->getType(),
                'all_items' => $plural,
                'add_new_item' => $new,
            ],
        ];
    }

    /**
     * Returns the ordered taxonomy terms
     * @return array
     */
    public function getOrderedTerms(): array
    {
        $terms = get_terms(
            [
                'taxonomy' => $this->getType(),
                'hide_empty' => false,
                'orderby' => 'name',
                'order' => 'ASC',
            ]
        );

        return \is_array($terms) ? $terms : [];
    }
}
