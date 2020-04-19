<?php

declare(strict_types=1);

if (taxonomy_exists('department')) {
    return;
}
add_action(
    'init',
    function () {
        $type = 'department';
        $labelSingular = 'Department';
        $labelPlural = 'Departments';

        register_taxonomy(
            $type,
            [],
            [
                'department',
                'hierarchical' => true,
                'show_ui' => true,
                'query_var' => true,
                'rewrite' => [
                    'slug' => $labelSingular,
                ],
                'labels' => [
                    'name' => $labelSingular,
                    'singular_name' => $type,
                    'all_items' => $labelPlural,
                    'add_new_item' => 'New ' . $labelSingular,
                ],
                'show_in_graphql' => true,
                'graphql_single_name' => $labelSingular,
                'graphql_plural_name' => $labelPlural,
            ]
        );
    }
);
