<?php
namespace Ababilithub\FlexELand\Package\Plugin\Taxonomy\Land\Type;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Taxonomy\Base\Taxonomy as BaseTaxonomy
};

use const Ababilithub\{
    FlexWordpress\PLUGIN_PRE_UNDS
};

if (!class_exists(__NAMESPACE__.'\Taxonomy')) 
{
    class Taxonomy extends BaseTaxonomy
    {
        use StandardMixin;
        protected function init(): void
        {
            $this->taxonomy = 'land-type';
            $this->taxonomy_slug = 'land-type';
            
            $this->init_hook();
            $this->init_service();
        }

        protected function init_hook(): void
        {
            //add_filter(PLUGIN_PRE_UNDS.'_admin_menu', [$this, 'add_menu_items']);
            add_action('init', [$this, 'init_taxonomy'], 97); 
            parent::init_hook();           
        }

        protected function init_service(): void
        {
            //
        }

        public function add_menu_items($menu_items = [])
        {
            $menu_items[] = [
                'type' => 'submenu',
                'parent_slug' => 'parent-slug',
                'page_title' => __('Land Type', 'flex-eland'),
                'menu_title' => __('Land Type', 'flex-eland'),
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy='.$this->taxonomy_slug,
                'callback' => null,
                'position' => 9,
            ];

            return $menu_items;
        }

        public function init_taxonomy()
        {
            
            $this->set_labels([
                'name'              => _x('Land Types', 'taxonomy general name', 'flex-eland'),
                'singular_name'     => _x('Land Type', 'taxonomy singular name', 'flex-eland'),
                'search_items'      => __('Search Land Types', 'flex-eland'),
                'all_items'         => __('All Land Types', 'flex-eland'),
                'parent_item'       => __('Parent Land Type', 'flex-eland'),
                'parent_item_colon' => __('Parent Land Type:', 'flex-eland'),
                'edit_item'         => __('Edit Land Type', 'flex-eland'),
                'update_item'       => __('Update Land Type', 'flex-eland'),
                'add_new_item'      => __('Add New Land Type', 'flex-eland'),
                'new_item_name'     => __('New Land Type Name', 'flex-eland'),
                'menu_name'         => __('Land Types', 'flex-eland'),
            ]);

            $this->set_args([
                'hierarchical' => true,
                'labels' => $this->labels,
                'public' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => ['slug' => $this->taxonomy_slug],
                'show_in_quick_edit' => true,
                'show_in_rest' => true,
                'meta_box_cb' => 'post_categories_meta_box',
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
            ]);

        }
    }
}