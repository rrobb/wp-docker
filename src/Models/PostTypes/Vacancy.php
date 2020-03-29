<?php
declare(strict_types=1);

namespace WPSite\Models\PostTypes;

use WPSite\Models\GenericPostType;

/**
 * Class Vacancy
 * @package WPSite\Models\PostTypes
 */
class Vacancy extends GenericPostType
{
    public const TYPE_SLUG = 'vacancy';

    /**
     * Allows for fields to be rendered in wp-admin, other fields dont show up
     * @const array
     */
    public const SUPPORTED_FIELDS = [
        'title',
        'thumbnail',
    ];

    /**
     * The post type menu icon
     * @const string
     */
    public const MENU_ICON = 'dashicons-editor-code';

    /**
     * Return menu icon
     * @return string
     */
    public function getMenuIcon(): string
    {
        return self::MENU_ICON;
    }

    /**
     * Returns the plural name of the post type
     * @return string
     */
    public function getPlural(): string
    {
        return 'Vacancies';
    }

    /**
     * Returns the single name of the post type
     * @return string
     */
    public function getSingle(): string
    {
        return 'Vacancy';
    }

    /**
     * Returns the post type
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_SLUG;
    }

    /**
     * Returns the slug
     * @return string
     */
    public function getSlug(): string
    {
        return self::TYPE_SLUG;
    }
}
