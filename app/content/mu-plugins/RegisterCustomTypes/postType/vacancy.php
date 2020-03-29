<?php

declare(strict_types=1);

if (post_type_exists('vacancy')) {
    return;
}
add_action(
    'init',
    function () {
        $label_singular = 'Vacancy';
        $label_plural = 'Vacancies';

        register_post_type(
            'vacancy',
            [
                'label' => $label_plural,
                'description' => '',
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'capability_type' => 'post',
                'hierarchical' => false,
                'query_var' => true,
                'has_archive' => true,
                'rewrite' => [
                    'slug' => 'vacancy',
                    'with_front' => false,
                ],
                'supports' => [
                    'title',
                    'editor',
                    'revisions',
                    'thumbnail',
                    'custom-fields',
                ],
                'labels' => [
                    'name' => $label_plural,
                    'singular_name' => $label_singular,
                    'menu_name' => $label_plural,
                    'add_new' => 'Add New',
                    'add_new_item' => 'Add New ' . $label_singular,
                    'edit' => 'Edit',
                    'edit_item' => 'Edit ' . $label_singular,
                    'new_item' => 'New ' . $label_singular,
                    'view' => 'View ' . $label_singular,
                    'view_item' => 'View ' . $label_singular,
                    'search_items' => 'Search ' . $label_plural,
                    'not_found' => 'No ' . $label_plural . ' Found',
                    'not_found_in_trash' => 'No ' . $label_plural . ' Found in Trash',
                    'parent' => 'Parent ' . $label_singular,
                ],
            ]
        );
    }
);
