<?php

declare(strict_types=1);

namespace RegisterCustomTypes;

use WP_Post_Type;
use WP_Taxonomy;

class SetupRelations
{
    public const PAGE_NAME = 'relations';
    public const OPTIONS_NAME = 'relations_options';
    public const PARENT_MENU = 'options-general.php';

    protected $taxonomies = [];
    protected $postTypes = [];
    protected $pageSections = [];

    /**
     * Relations constructor.
     * Set up all actions
     */
    public function __construct()
    {
        add_action('init', [$this, 'collectTypes']);
        add_action('init', [$this, 'registerRelations']);
        add_action('admin_init', [$this, 'relationsSettingsInit']);
        add_action('admin_menu', [$this, 'relationsOptionsPage']);
        add_action('whitelist_options', [$this, 'whitelistCustomOptionsPage'], 11);
    }

    /**
     * Collect all taxonomies and post types
     * @noinspection PhpUnused
     */
    public function collectTypes()
    {
        $this->taxonomies = get_taxonomies(
            [
                'public' => true,
                '_builtin' => false,
            ],
            'objects'
        );
        $this->postTypes = get_post_types(
            [
                'public' => true,
            ],
            'objects'
        );
    }

    /**
     * Apply the relationship registrations as found in option `relations_options`
     * @noinspection PhpUnused
     */
    public function registerRelations()
    {
        foreach ($this->taxonomies as $taxonomy) {
            $options = get_option(sprintf('relations_section__%s', $taxonomy->name));
            if ($options === false) {
                continue;
            }
            foreach ($options as $postType => $value) {
                register_taxonomy_for_object_type($taxonomy->name, $postType);
            }
        }
    }

    /**
     * custom option and settings
     * @noinspection PhpUnused
     */
    public function relationsSettingsInit()
    {
        register_setting(
            'options_general',
            self::OPTIONS_NAME
        );

        foreach ($this->taxonomies as $taxonomy) {
            $this->addTaxonomySection($taxonomy);
        }
    }

    /**
     * @param WP_Taxonomy $taxonomy
     */
    protected function addTaxonomySection(WP_Taxonomy $taxonomy)
    {
        $section = sprintf('relations_section__%s', $taxonomy->name);
        $this->add_settings_section(
            $section,
            $taxonomy->label,
            [$this, 'displayTaxonomySection'],
            self::PAGE_NAME
        );

        foreach ($this->postTypes as $postType) {
            $this->addPostField($postType, $section);
        }
    }

    /**
     * Wrapper for wp's `add_settings_section()` that tracks custom sections
     * @param string $id
     * @param string $title
     * @param callable $callback
     * @param string $page
     */
    private function add_settings_section(
        string $id,
        string $title,
        callable $callback,
        string $page
    ): void {
        add_settings_section(
            $id,
            $title,
            $callback,
            $page
        );
        if ($id === $page) {
            return;
        }
        if (!isset($this->pageSections[$page])) {
            $this->pageSections[$page] = [];
        }
        $this->pageSections[$page][] = $id;
    }

    /**
     * Adds a post type field to the taxonomy section
     * @param WP_Post_Type $postType
     * @param string $section
     */
    protected function addPostField(WP_Post_Type $postType, string $section)
    {
        $id = sprintf('%s__%s', $section, $postType->name);
        $field = sprintf('%s[%s]', $section, $postType->name);
        add_settings_field(
            $id,
            '',
            [$this, 'displayPostField'],
            self::PAGE_NAME,
            $section,
            [
                'label_for' => $id,
                'class' => 'relations_row',
                'relations_custom_data' => 'custom',
                'section' => $section,
                'name' => $postType->name,
                'label' => $postType->label,
                'field' => $field,
            ]
        );
    }

    /**
     * Custom option and settings:
     * $args have the following keys defined: title, id, callback.
     * the values are defined at the add_settings_section() function.
     * callback functions
     * @param array $args
     */
    public function displayTaxonomySection(array $args)
    {
        $name = str_replace('Taxonomy ', '', $args['title']);
        $id = esc_attr($args['id']);
        $directions = sprintf('Select all PostTypes to use taxonomy "%s":', $name);

        echo "<p id=\"{$id}\">{$directions}</p>";
    }

    /**
     * Displays the post field
     * the "label_for" key value is used for the "for" attribute of the <label>.
     * the "class" key value is used for the "class" attribute of the <tr> containing the field.
     * @param $args
     */
    public function displayPostField($args)
    {
        $options = get_option($args['section']);
        $value = $options[$args['name']] ?? null;
        $checked = $value ? ' checked' : '';

        echo <<<HTML
            <label for="{$args['label_for']}" class="{$args['class']}" value="{$args['name']}">
                <input type="checkbox" id="{$args['label_for']}" name="{$args['field']}" {$checked}/>
                {$args['label']}
            </label>
HTML;
    }

    /**
     * Add the options page to the 'Settings' menu
     * @noinspection PhpUnused
     */
    public function relationsOptionsPage()
    {
        add_submenu_page(
            self::PARENT_MENU,
            'Manage Taxonomy-PostType relations',
            'Manage relations',
            'manage_options',
            self::PAGE_NAME,
            [$this, 'handleRelationsOptionsPage']
        );
    }

    /**
     * Handle updating of settings and display of the page
     */
    public function handleRelationsOptionsPage()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (isset($_GET['settings-updated'])) {
            add_settings_error(
                'relations_messages',
                'relations_message',
                __('Settings Saved', self::PAGE_NAME),
                'updated'
            );
        }

        $this->displayRelationsOptionsPage();
    }

    /**
     * Display the options page
     */
    protected function displayRelationsOptionsPage()
    {
        $title = esc_html(get_admin_page_title());
        settings_errors('relations_messages');
        ob_start();
        settings_fields(self::PAGE_NAME);
        do_settings_sections(self::PAGE_NAME);
        submit_button('Save Settings');
        $content = ob_get_contents();
        ob_end_clean();

        echo <<<HTML
        <div class="wrap">
            <h1>{$title}</h1>
            <form action="options.php" method="post">
                {$content}
            </form>
        </div>
HTML;
    }

    /**
     * White-lists options on custom pages.
     * Workaround for second issue: http://j.mp/Pk3UCF
     * @param array $whitelistOptions
     * @return array
     * @noinspection PhpUnused
     */
    public function whitelistCustomOptionsPage(array $whitelistOptions): array
    {
        return array_merge($whitelistOptions, $this->pageSections);
    }
}