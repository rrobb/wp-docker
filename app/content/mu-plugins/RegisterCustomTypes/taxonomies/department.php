<?php

if (!taxonomy_exists('department')) {
    add_action('init', function () {
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
                ]
            ]
        );
    });
}