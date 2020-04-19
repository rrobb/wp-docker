<?php

declare(strict_types=1);

namespace RegisterCustomTypes;

class CustomTaxonomy extends CustomType
{
    protected $args = [
        'hierarchical' => true,
        'show_ui' => true,
        'query_var' => true,
        'show_in_graphql' => true,
    ];

    public function register(): void
    {
        if (taxonomy_exists($this->slug)) {
            return;
        }
        add_action(
            'init',
            function () {
                register_taxonomy(
                    $this->slug,
                    [],
                    array_merge_recursive(
                        [
                            $this->slug,
                            'rewrite' => [
                                'slug' => $this->singular,
                            ],
                            'labels' => [
                                'name' => $this->singular,
                                'singular_name' => $this->slug,
                                'all_items' => $this->plural,
                                'add_new_item' => 'New ' . $this->singular,
                            ],
                            'graphql_single_name' => $this->singular,
                            'graphql_plural_name' => $this->plural,
                        ],
                        $this->args
                    )
                );
            }
        );
    }
}