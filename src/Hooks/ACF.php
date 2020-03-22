<?php
declare(strict_types=1);

namespace WPSite\Hooks;

/**
 * Class ACF
 *
 * @author Rob Burgers <rob@endouble.com>
 * @package WPSite\Hooks
 */
class ACF implements HooksInterface
{
    /**
     * Add custom conditions to the ACF location box. Specifically allows using the 'Page controller' setting as a
     * condition for showing custom fields
     */
    public function locationTweaks(): void
    {
        /**
         * Add a menu item 'Page controller' to Heading 'Custom' of the 'Show this group as' select box under 'Location'
         * on the ACF Groups page.
         */
        add_filter('acf/location/rule_types', function ($choices) {

            $choices['Custom']['page_controller'] = 'Page controller';

            return $choices;
        });

        /**
         * Add a select box with Page controller values under 'Location' on the ACF Groups page.
         */
        add_filter('acf/location/rule_values/page_controller', function ($choices) {

            global $wpdb;
            $values = $wpdb->get_col("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = 'controller' and meta_value != '' GROUP BY meta_value ORDER BY meta_value");

            if (!$values) {
                return $choices;
            }

            foreach ($values as $key => $value) {
                $choices[$value] = $value;
            }

            return $choices;
        });

        /**
         * Test whether the current page uses
         */
        add_filter('acf/location/rule_match/page_controller', function ($match, $rule) {
            $currId = get_the_ID();

            $pageController = get_post_meta($currId, 'controller', true);
            $selectedPageController = $rule['value'];

            if ($rule['operator'] === '==') {
                return $pageController === $selectedPageController;
            }

            if ($rule['operator'] === '!=') {
                return $pageController !== $selectedPageController;
            }

            return $match;
        }, 10, 3);
    }


    /**
     * Attach ACF methods
     */
    public function attach(): void
    {
        $this->locationTweaks();
    }
}
