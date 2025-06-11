<?php
namespace Ababilithub\FlexELand\Package\Plugin\Menu;

use Ababilithub\{
    FlexWordpress\Package\Menu\Base\Menu as BaseMenu,
    FlexELand\Package\Plugin\Language\Arabic\Alphabet\Presentation\Template\Template as AlphabetTemplate,
};

use const Ababilithub\{
    FlexELand\PLUGIN_NAME,
    FlexELand\PLUGIN_DIR,
    FlexELand\PLUGIN_URL,
    FlexELand\PLUGIN_FILE,
    FlexELand\PLUGIN_PRE_UNDS,
    FlexELand\PLUGIN_PRE_HYPH,
    FlexELand\PLUGIN_VERSION
};

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

if (!class_exists(__NAMESPACE__.'\Menu')) 
{
    /**
     * Concrete Class ThemeSettingsMenu
     * Implements the WordPress admin menu for theme settings
     */
    class Menu extends BaseMenu
    {
        /**
         * Constructor to define menu properties and submenus
         */
        public function __construct()
        {
            $this->page_title    = 'Flex ELand';
            $this->menu_title    = 'Flex ELand';
            $this->capability    = 'manage_options';
            $this->menu_slug     = 'flex-eland';
            $this->callback      = [$this, 'render_page'];
            $this->menu_icon     = 'dashicons-admin-customizer';
            $this->menu_position = 9;

            parent::__construct();

            // Add submenus dynamically
            $this->add_submenu([
                'page_title' => 'Document',
                'menu_title' => 'Document',
                'capability' => 'manage_options',
                'slug'       => 'edit.php?post_type=flexdoc',
                'callback'   => [$this, 'render_submenu']
            ]);

            $this->add_submenu([
                'page_title' => 'Mouza',
                'menu_title' => 'Mouza',
                'capability' => 'manage_options',
                'slug'       => 'edit.php?post_type=fmouza',
                'callback'   => [$this, 'render_submenu']
            ]);

            $this->add_submenu([
                'page_title' => 'Land Record',
                'menu_title' => 'Land Record',
                'capability' => 'manage_options',
                'slug'       => 'edit.php?post_type=flrecord',
                'callback'   => [$this, 'render_submenu']
            ]);

            add_shortcode('flex_multilingual_alphabet_grid', array($this,'render_page'));

        }

        /**
         * Renders the main settings page
         */
        public function render_page(): void
        {
            $template = new AlphabetTemplate();
            echo AlphabetTemplate::alphabet_grid([]);
        }

        /**
         * Renders the submenu pages
         */
        public function render_submenu(): void
        {
            $template = new AlphabetTemplate();
            echo AlphabetTemplate::alphabet_grid([]);
        }

        /**
         * Get the page title
         */
        protected function get_page_title(): string
        {
            return $this->page_title;
        }

        /**
         * Get the menu title
         */
        protected function get_menu_title(): string
        {
            return $this->menu_title;
        }

        /**
         * Get the menu capability
         */
        protected function get_menu_capability(): string
        {
            return $this->capability;
        }

        /**
         * Get the menu slug
         */
        protected function get_menu_slug(): string
        {
            return $this->menu_slug;
        }

        /**
         * Get the callback function
         */
        protected function get_callback(): callable 
        {
            return is_callable($this->callback) ? $this->callback : '__return_false';
        }
    }
}
