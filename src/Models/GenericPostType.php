<?php
declare(strict_types=1);

namespace WPSite\Models;

/**
 * Class GenericPostType
 * Creates a post type based on the generic rules
 * @package Endouble\Models
 */
abstract class GenericPostType implements PostTypeInterface
{
    /** @const bool */
    private const HAS_ARCHIVE = false;

    /** @const string */
    private const MENU_ICON = 'dashicons-align-left';

    /** @const array */
    public const SUPPORTED_FIELDS = [
        'title',
        'editor',
        'excerpt',
        'thumbnail',
    ];

    /**
     * The post type name in the plural form
     * @return string
     */
    abstract public function getPlural(): string;

    /**
     * The post type name in the single form. Can be overwritten.
     * @return string
     */
    public function getSingle(): string
    {
        return $this->getType();
    }

    /**
     * Gets the slug for the url. Can be overwritten
     * @return string
     */
    public function getSlug(): string
    {
        return strtolower($this->getPlural());
    }

    /**
     * Return menu icon
     * @return string
     */
    public function getMenuIcon(): string
    {
        return self::MENU_ICON;
    }

    /**
     * Returns the arguments for the post type
     * @return array
     */
    public function getArgs(): array
    {
        $type = $this->getType();
        $plural = $this->getPlural();
        $single = $this->getSingle();
        $new = 'Nieuwe ' . strtolower($single);
        $edit = $single . ' bewerken';

        return [
            '_builtin' => false,
            'label' => $plural,
            'labels' => [
                'add_new' => $new,
                'add_new_item' => $new,
                'all_items' => $plural,
                'name' => $plural,
                'edit_item' => $edit,
                'singular_name' => $type,
                'new_item' => $new,
            ],
            'menu_icon' => $this->getMenuIcon(),
            'public' => true,
            'show_ui' => true,
            'capability_type' => 'post',
            'hierarchical' => true,
            'has_archive' => self::HAS_ARCHIVE,
            'query_var' => $type,
            'menu_position' => 5,
            'rewrite' => [
                'slug' => $this->getSlug(),
                'feeds' => true,
            ],
            'supports' => static::SUPPORTED_FIELDS,
        ];
    }
}
