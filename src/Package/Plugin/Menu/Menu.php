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
                'page_title' => 'Flex Bangla Land',
                'menu_title' => 'Flex Bangla Land',
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
            echo '<h1>Flex Bangla Land Dashboard</h1>';
            echo '<p>Welcome to Flex Bangla Land administration panel.</p>';
            echo '</div>';
        }

        /**
         * Render settings page content
         */
        public function render_settings_page()
        {
            if (isset($_GET['view_meta'])) 
            {
                echo $this->render_flex_eland_registrations();
                $post_id = intval($_GET['view_meta']);
                echo $this->render_post_meta_table($post_id);
                return;
            }
        }

        public function render_post_meta_table($post_id)
        {
                // Get all post meta
                $all_meta = get_post_meta($post_id);
                
                echo '<div class="wrap">';
                echo '<h1>Post Meta Data for #' . esc_html($post_id) . '</h1>';
                echo '<p><a href="' . get_edit_post_link($post_id) . '">&larr; Back to post editor</a></p>';
                
                if (empty($all_meta)) {
                    echo '<div class="notice notice-info"><p>No meta data found for this post.</p></div>';
                    echo '</div>';
                    return;
                }

                echo '<table class="widefat fixed striped">';
                echo '<thead>
                    <tr>
                        <th width="25%">Meta Key</th>
                        <th width="75%">Meta Value</th>
                    </tr>
                </thead>
                <tbody>';

                foreach ($all_meta as $meta_key => $meta_values) {
                    echo '<tr>';
                    echo '<td><strong>' . esc_html($meta_key) . '</strong></td>';
                    echo '<td>';
                    
                    foreach ($meta_values as $value) {
                        $unserialized = maybe_unserialize($value);
                        
                        if (is_array($unserialized) || is_object($unserialized)) {
                            echo '<pre>' . esc_html(print_r($unserialized, true)) . '</pre>';
                        } else {
                            echo esc_html($value);
                        }
                        
                        echo '<hr style="margin: 5px 0; border: 0; border-top: 1px dashed #ccc;">';
                    }
                    
                    echo '</td>';
                    echo '</tr>';
                }

                echo '</tbody></table>';
                
                // Add styling
                echo '<style>
                    .wrap { margin: 20px; }
                    table.widefat { border-collapse: collapse; margin-top: 20px; }
                    table.widefat th, 
                    table.widefat td { padding: 10px; border: 1px solid #e5e5e5; vertical-align: top; }
                    table.widefat th { background-color: #f7f7f7; font-weight: 600; }
                    table.widefat pre { 
                        margin: 0; 
                        white-space: pre-wrap; 
                        max-width: 100%; 
                        overflow-x: auto;
                        background: #f5f5f5;
                        padding: 5px;
                        border-radius: 3px;
                    }
                    .notice { margin: 20px 0 !important; }
                </style>';
                
                echo '</div>';
            
        }

        public function render_flex_eland_registrations() 
        {
            global $wp_post_types, $wp_taxonomies, $shortcode_tags, $wp_filter;

            echo '<div class="wrap">';
            echo '<h1>Flex-Eland Plugin Registrations</h1>';
            
            // Tab navigation
            echo '<nav class="nav-tab-wrapper">';
            echo '<a href="#posttypes" class="nav-tab">Post Types</a>';
            echo '<a href="#taxonomies" class="nav-tab">Taxonomies</a>';
            echo '<a href="#shortcodes" class="nav-tab">Shortcodes</a>';
            echo '<a href="#hooks" class="nav-tab">Hooks</a>';
            echo '</nav>';

            // Post Types tab
            echo '<div id="posttypes" class="tab-content">';
            echo '<h2>Registered Post Types</h2>';
            $flex_post_types = array_filter($wp_post_types, function($post_type) {
                return $this->is_flex_eland_registration($post_type->_builtin ? '' : $post_type->name);
            });
            $this->render_registration_table($flex_post_types, ['name', 'label', 'public', 'show_ui']);
            echo '</div>';

            // Taxonomies tab
            echo '<div id="taxonomies" class="tab-content">';
            echo '<h2>Registered Taxonomies</h2>';
            $flex_taxonomies = array_filter($wp_taxonomies, function($taxonomy) {
                return $this->is_flex_eland_registration($taxonomy->_builtin ? '' : $taxonomy->name);
            });
            $this->render_registration_table($flex_taxonomies, ['name', 'label', 'public', 'show_ui']);
            echo '</div>';

            // Shortcodes tab
            echo '<div id="shortcodes" class="tab-content">';
            echo '<h2>Registered Shortcodes</h2>';
            $flex_shortcodes = array_filter($shortcode_tags, function($key) {
                return $this->is_flex_eland_registration($key);
            }, ARRAY_FILTER_USE_KEY);
            $this->render_simple_list($flex_shortcodes);
            echo '</div>';

            // Hooks tab
            echo '<div id="hooks" class="tab-content">';
            echo '<h2>Registered Hooks</h2>';
            $flex_hooks = [];
            
            foreach ($wp_filter as $hook_name => $hook) {
                if (!$this->is_flex_eland_registration($hook_name)) {
                    continue;
                }
                
                foreach ($hook->callbacks as $priority => $callbacks) {
                    foreach ($callbacks as $callback) {
                        if ($this->is_flex_eland_callback($callback['function'])) {
                            $flex_hooks[] = [
                                'hook' => $hook_name,
                                'callback' => $this->format_callback($callback['function']),
                                'priority' => $priority,
                                'args' => $callback['accepted_args'],
                                'type' => $this->get_hook_type($hook_name)
                            ];
                        }
                    }
                }
            }
            
            $this->render_hooks_table($flex_hooks);
            echo '</div>';

            // Styles and scripts
            $this->render_debug_styles();
            echo '</div>';
        }

        /**
         * Check if a registration belongs to Flex-Eland
         */
        private function is_flex_eland_registration($name) {
            if (empty($name)) return false;
            
            // Check for plugin prefix patterns
            $prefixes = ['flex_eland', 'flex-eland', 'fldeed', 'fl'];
            
            foreach ($prefixes as $prefix) {
                if (strpos($name, $prefix) === 0) {
                    return true;
                }
            }
            
            // Check namespace pattern
            if (strpos($name, 'Ababilithub\\FlexELand') !== false) {
                return true;
            }
            
            return false;
        }

        /**
         * Check if a callback belongs to Flex-Eland
         */
        private function is_flex_eland_callback($callback) {
            if (is_string($callback)) {
                return strpos($callback, 'flex_eland') !== false || 
                    strpos($callback, 'Ababilithub\\FlexELand') !== false;
            }
            
            if (is_array($callback)) {
                $class = is_object($callback[0]) ? get_class($callback[0]) : $callback[0];
                return strpos($class, 'Ababilithub\\FlexELand') !== false;
            }
            
            if (is_object($callback)) {
                return strpos(get_class($callback), 'Ababilithub\\FlexELand') !== false;
            }
            
            return false;
        }

        /**
         * Determine hook type (action/filter)
         */
        private function get_hook_type($hook_name) {
            return strpos($hook_name, 'filter_') === 0 ? 'filter' : 'action';
        }

        /**
         * Render registration table
         */
        private function render_registration_table($items, $columns) {
            if (empty($items)) {
                echo '<p>No Flex-Eland registrations found.</p>';
                return;
            }
            
            echo '<table class="flex-debug-table">';
            echo '<thead><tr>';
            foreach ($columns as $column) {
                echo '<th>' . ucfirst(str_replace('_', ' ', $column)) . '</th>';
            }
            echo '</tr></thead><tbody>';
            
            foreach ($items as $key => $item) {
                echo '<tr>';
                foreach ($columns as $column) {
                    echo '<td>';
                    if ($column === 'name') {
                        echo '<strong>' . esc_html($key) . '</strong>';
                    } else {
                        $value = is_object($item) ? ($item->$column ?? '') : ($item[$column] ?? '');
                        echo esc_html($value);
                    }
                    echo '</td>';
                }
                echo '</tr>';
            }
            
            echo '</tbody></table>';
        }

        /**
         * Render hooks table
         */
        private function render_hooks_table($hooks) {
            if (empty($hooks)) {
                echo '<p>No Flex-Eland hooks found.</p>';
                return;
            }
            
            echo '<table class="flex-debug-table">';
            echo '<thead><tr>
                <th>Type</th>
                <th>Hook</th>
                <th>Callback</th>
                <th>Priority</th>
                <th>Args</th>
            </tr></thead><tbody>';
            
            foreach ($hooks as $hook) {
                echo '<tr>
                    <td>' . esc_html($hook['type']) . '</td>
                    <td>' . esc_html($hook['hook']) . '</td>
                    <td class="hook-callback">' . $hook['callback'] . '</td>
                    <td>' . esc_html($hook['priority']) . '</td>
                    <td>' . esc_html($hook['args']) . '</td>
                </tr>';
            }
            
            echo '</tbody></table>';
        }

        /**
         * Render debug page styles
         */
        private function render_debug_styles() {
            echo '<style>
                .wrap { margin: 20px; }
                .nav-tab-wrapper { margin: 20px 0; }
                .tab-content { display: none; padding: 20px 0; }
                .tab-content.active { display: block; }
                
                .flex-debug-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 15px 0;
                }
                
                .flex-debug-table th,
                .flex-debug-table td {
                    padding: 8px 12px;
                    border: 1px solid #ddd;
                    text-align: left;
                    vertical-align: top;
                }
                
                .flex-debug-table th {
                    background-color: #f5f5f5;
                    font-weight: 600;
                }
                
                .flex-debug-table tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                
                .hook-callback {
                    font-family: monospace;
                    font-size: 13px;
                    word-break: break-all;
                }
            </style>';
            
            echo '<script>
                jQuery(document).ready(function($) {
                    $(".nav-tab").click(function(e) {
                        e.preventDefault();
                        $(".nav-tab").removeClass("nav-tab-active");
                        $(this).addClass("nav-tab-active");
                        $(".tab-content").removeClass("active");
                        $($(this).attr("href")).addClass("active");
                    });
                    $(".nav-tab:first").click();
                });
            </script>';
        }

        /**
         * Render a simple list of items
         */
        private function render_simple_list($items)
        {
            if (empty($items)) {
                echo '<p>No items found.</p>';
                return;
            }
            
            echo '<ul>';
            foreach ($items as $key => $value) {
                echo '<li><strong>' . esc_html($key) . '</strong> => ' . 
                    (is_string($value) ? esc_html($value) : 'Callback function') . '</li>';
            }
            echo '</ul>';
        }
        /**
         * Format callback for display
         */
        private function format_callback($callback)
        {
            if (is_string($callback)) {
                return esc_html($callback);
            }
            
            if (is_array($callback)) {
                $class = is_object($callback[0]) ? get_class($callback[0]) : $callback[0];
                return esc_html($class . '::' . $callback[1]);
            }
            
            if (is_object($callback)) {
                return esc_html(get_class($callback) . '->__invoke()');
            }
            
            return 'Unknown callback type';
        }
    }
}