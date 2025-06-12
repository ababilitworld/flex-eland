<?php
namespace Ababilithub\FlexELand\Package\Plugin\Taxonomy\Document\Catagory;

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexELand\Package\Plugin\Taxonomy\Document\Setting\Setting as Setting,
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

if (!class_exists(__NAMESPACE__.'\Taxonomy')) 
{
    class Taxonomy 
    {
        use StandardMixin;
        private $taxonomy;
        private $taxonomy_slug;
        private $post_types = [];
        
        public function __construct()
        {
            $this->init();
        }

        private function init()
        {
            $this->taxonomy = 'document-category';  // Changed from document_catagory
            $this->taxonomy_slug = 'document-category';  // Changed from document-catagory
            $this->init_hook();
            $this->init_service();
        }

        private function init_hook()
        {
            add_filter(PLUGIN_PRE_UNDS.'_admin_menu', [$this, 'add_menu_items']);
            add_action('init', [$this, 'register_taxonomy'], 0);            
        }

        private function init_service()
        {
            //$this->settings = Setting::getInstance();
        }

        /**
         * Add default menu items (can be overridden by other plugins/themes)
         */
        public function add_menu_items($menu_items = [])
        {

            // Default submenu items
            $menu_items[] = [
                'type' => 'submenu',
                'parent_slug' => 'flex-eland',
                'page_title' => 'Document Catagory',
                'menu_title' => 'Document Catagory',
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy='.$this->taxonomy_slug,
                'callback' => null, // Uses default post type listing
                'position' => 2,
            ];

            return $menu_items;
        }

        /**
         * Register taxonomy with WordPress
         */
        public function register_taxonomy()
        {
            $labels = [
                'name'              => _x('Document Categories', 'taxonomy general name', 'flex-eland'),
                'singular_name'     => _x('Document Category', 'taxonomy singular name', 'flex-eland'),
                'search_items'      => __('Search Document Categories', 'flex-eland'),
                'all_items'         => __('All Document Categories', 'flex-eland'),
                'parent_item'       => __('Parent Document Category', 'flex-eland'),
                'parent_item_colon' => __('Parent Document Category:', 'flex-eland'),
                'edit_item'         => __('Edit Document Category', 'flex-eland'),
                'update_item'       => __('Update Document Category', 'flex-eland'),
                'add_new_item'      => __('Add New Document Category', 'flex-eland'),
                'new_item_name'     => __('New Document Category Name', 'flex-eland'),
                'menu_name'         => __('Document Categories', 'flex-eland'),
            ];

            $args = [
                'hierarchical' => true, // true for category-like, false for tag-like
                'labels' => $labels,
                'public' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => ['slug' => $this->taxonomy_slug],
                'show_in_quick_edit' => true,
                'show_in_rest' => true, // Critical for Gutenberg editor
                'meta_box_cb' => 'post_categories_meta_box', // Use default WordPress meta box
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
            ];

            register_taxonomy($this->taxonomy_slug, $this->post_types, $args);

        }

        /**
         * Get taxonomy slug
         */
        public function get_taxonomy_slug(): string
        {
            return $this->taxonomy_slug;
        }

        /**
         * Add post type to this taxonomy
         */
       public function add_post_type(string $post_type)
        {
            if (!in_array($post_type, $this->post_types, true)) 
            {
                $this->post_types[] = $post_type;

                // Ensure taxonomy exists
                if (taxonomy_exists($this->taxonomy)) 
                {
                    // Check if taxonomy is already registered to this post type
                    $object_taxonomies = get_object_taxonomies($post_type, 'names');
                    if (!in_array($this->taxonomy, $object_taxonomies, true)) 
                    {
                        register_taxonomy_for_object_type($this->taxonomy, $post_type);
                    }
                }
            }

            return $this;
        }

    }
}