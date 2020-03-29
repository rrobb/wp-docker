<?php

declare(strict_types=1);

if (taxonomy_exists('country')) {
    return;
}
add_action(
    'init',
    function () {
        $type = 'country';
        $labelSingular = 'Country';
        $labelPlural = 'Countries';

        register_taxonomy(
            $type,
            [],
            [
                'country',
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
            ]
        );
    }
);
