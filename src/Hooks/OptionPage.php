<?php
declare(strict_types=1);

namespace WPSite\Hooks;

/**
 * Class Options
 *
 * Adds option pages to the theme
 * @package WPSite\Hooks
 */
class OptionPage implements HooksInterface
{
    /**
     * Add settings for ACF
     */
    public function ACFOptionPages(): void
    {
        if (!\function_exists('acf_add_options_page')) {
            return;
        }

        // Section General
        acf_add_options_page([
            'page_title' => 'General',
            'menu_title' => 'General',
            'menu_slug' => 'website-general',
            'capability' => 'edit_posts',
            'redirect' => false,
        ]);
    }

    /**
     * Attach all action and filters that are defined in the Hook class
     */
    public function attach(): void
    {
        add_action('init', [$this, 'ACFOptionPages']);
    }
}
