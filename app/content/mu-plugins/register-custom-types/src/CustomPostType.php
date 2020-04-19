<?php

declare(strict_types=1);

namespace RegisterCustomTypes;

class CustomPostType extends CustomType
{
    protected $args = [
        'description' => '',
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'query_var' => true,
        'has_archive' => true,
        'rewrite' => [
            'slug' => 'store',
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
            'add_new' => 'Add New',
            'edit' => 'Edit',
        ],
    ];

    public function register(): void
    {
        if (post_type_exists($this->slug)) {
            return;
        }
        add_action(
            'init',
            function () {
                register_post_type(
                    $this->slug,
                    array_merge_recursive(
                        [
                            'label' => $this->singular,
                            'labels' => [
                                'name' => $this->singular,
                                'singular_name' => $this->plural,
                                'menu_name' => $this->singular,
                                'add_new_item' => 'Add New ' . $this->plural,
                                'edit_item' => 'Edit ' . $this->plural,
                                'new_item' => 'New ' . $this->plural,
                                'view' => 'View ' . $this->plural,
                                'view_item' => 'View ' . $this->plural,
                                'search_items' => 'Search ' . $this->singular,
                                'not_found' => 'No ' . $this->singular . ' Found',
                                'not_found_in_trash' => 'No ' . $this->singular . ' Found in Trash',
                                'parent' => 'Parent ' . $this->plural,
                            ],
                        ],
                        $this->args
                    )
                );
            }
        );
    }
}