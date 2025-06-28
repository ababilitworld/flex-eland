<?php
namespace Ababilithub\FlexELand\Package\Plugin\Taxonomy\V1\Land\Document\Category;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Taxonomy\V1\Base\Taxonomy as BaseTaxonomy
};

use const Ababilithub\{
    FlexWordpress\PLUGIN_PRE_UNDS
};

if (!class_exists(__NAMESPACE__.'\Taxonomy')) 
{
    class Taxonomy extends BaseTaxonomy
    {
        public function init(): void
        {
            $this->taxonomy = 'land-doc-cat';
            $this->slug = 'land-doc-cat';

            $this->set_labels([
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
            ]);

            $this->set_args([
                'hierarchical' => true,
                'labels' => $this->labels,
                'public' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => ['slug' => $this->slug],
                'show_in_quick_edit' => true,
                'show_in_rest' => true,
                'meta_box_cb' => 'post_categories_meta_box',
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
            ]);

            $this->init_service();
            $this->init_hook();
            
        }

        protected function init_service(): void
        {
            //
        }

        protected function init_hook(): void
        {
            //add_action('init', [$this, 'init_taxonomy'], 97);
            //add_filter(PLUGIN_PRE_UNDS.'_admin_menu', [$this, 'add_menu_items']);
        }

        public function add_menu_items($menu_items = [])
        {
            $menu_items[] = [
                'type' => 'submenu',
                'parent_slug' => 'parent-slug',
                'page_title' => __('Document Category', 'flex-eland'),
                'menu_title' => __('Document Category', 'flex-eland'),
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy='.$this->slug,
                'callback' => null,
                'position' => 9,
            ];

            return $menu_items;
        }
    }
}