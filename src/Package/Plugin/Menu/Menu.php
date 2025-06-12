<?php
namespace Ababilithub\FlexELand\Package\Plugin\Menu;

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
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

(defined('ABSPATH') && defined('WPINC')) || exit();

if (!class_exists(__NAMESPACE__.'\Menu')) 
{
    /**
     * Concrete Class ThemeSettingsMenu
     * Implements the WordPress admin menu for theme settings
     */
    class Menu 
    {
        use StandardMixin;

        /**
         * Constructor to define menu properties and submenus
         */
        public function __construct()
        {
            // Add filter to collect menu items
            add_filter(PLUGIN_PRE_UNDS.'_admin_menu', [$this, 'add_menu_items']);
            // Add action to register menus
            add_action('admin_menu', [$this, 'register_admin_menus']);
        }

        /**
         * Add default menu items (can be overridden by other plugins/themes)
         */
        public function add_menu_items($menu_items = [])
        {
            // Default main menu item
            $menu_items[] = [
                'type' => 'menu',
                'page_title' => 'Flex ELand',
                'menu_title' => 'Flex ELand',
                'capability' => 'manage_options',
                'menu_slug' => 'flex-eland',
                'callback' => [$this, 'render_main_page'],
                'icon' => 'dashicons-admin-customizer',
                'position' => 9
            ];

            $menu_items[] = [
                'type' => 'submenu',
                'parent_slug' => 'flex-eland',
                'page_title' => 'Settings',
                'menu_title' => 'Settings',
                'capability' => 'manage_options',
                'menu_slug' => 'flex-eland-settings',
                'callback' => [$this, 'render_settings_page'],
                'position' => 1,
            ];

            return $menu_items;
        }


        /**
         * Register all admin menus and submenus
         */
        public function register_admin_menus()
        {
            // Get all menu items from filter
            $menu_items = apply_filters(PLUGIN_PRE_UNDS.'_admin_menu', []);

            foreach ($menu_items as $item) {
                if ($item['type'] === 'menu') {
                    add_menu_page(
                        $item['page_title'],
                        $item['menu_title'],
                        $item['capability'],
                        $item['menu_slug'],
                        $item['callback'],
                        $item['icon'],
                        $item['position']
                    );
                } 
                elseif ($item['type'] === 'submenu') {
                    add_submenu_page(
                        $item['parent_slug'],
                        $item['page_title'],
                        $item['menu_title'],
                        $item['capability'],
                        $item['menu_slug'],
                        $item['callback'],
                        $item['position']
                    );
                }
            }
        }

        /**
         * Render main page content
         */
        public function render_main_page()
        {
            echo '<div class="wrap">';
            echo '<h1>Flex ELand Dashboard</h1>';
            echo '<p>Welcome to Flex ELand administration panel.</p>';
            echo '</div>';
        }

        /**
         * Render settings page content
         */
        public function render_settings_page()
        {
            echo '<div class="wrap">';
            echo '<h1>Flex ELand Settings</h1>';
            echo '<form method="post" action="options.php">';
            // Add your settings fields here
            settings_fields('flex-eland-settings-group');
            do_settings_sections('flex-eland-settings');
            submit_button();
            echo '</form>';
            echo '</div>';
        }
    }
}