<?php
namespace Ababilithub\FlexELand\Package\Plugin\Taxonomy\Land\Mouza;

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
            $this->taxonomy = 'land-mouza';
            $this->slug = 'land-mouza';

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
                'page_title' => __('Land Mouza', 'flex-eland'),
                'menu_title' => __('Land Mouza', 'flex-eland'),
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy='.$this->slug,
                'callback' => null,
                'position' => 9,
            ];

            return $menu_items;
        }

        public function init_taxonomy()
        {
            
            $this->set_labels([
                'name'              => _x('Land Mouzas', 'taxonomy general name', 'flex-eland'),
                'singular_name'     => _x('Land Mouza', 'taxonomy singular name', 'flex-eland'),
                'search_items'      => __('Search Land Mouzas', 'flex-eland'),
                'all_items'         => __('All Land Mouzas', 'flex-eland'),
                'parent_item'       => __('Parent Land Mouza', 'flex-eland'),
                'parent_item_colon' => __('Parent Land Mouza:', 'flex-eland'),
                'edit_item'         => __('Edit Land Mouza', 'flex-eland'),
                'update_item'       => __('Update Land Mouza', 'flex-eland'),
                'add_new_item'      => __('Add New Land Mouza', 'flex-eland'),
                'new_item_name'     => __('New Land Mouza Name', 'flex-eland'),
                'menu_name'         => __('Land Mouzas', 'flex-eland'),
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

            $this->set_terms([
                $this->generate_term_data(
                    'kashimpur',
                    'Kashimpur',
                    'Union Auliapur',
                    [
                        'type' => 'mouza',
                        'parent' => 'thana-kotwali',
                        'union' => 'Auliapur',
                        'thana' => 'Kotwali',
                        'upazila' => 'Dinajpur Sadar',
                        'district' => 'Dinajpur',
                        'division' => 'Rangpur',
                        'jl_no' => 101
                    ]
                ),
                $this->generate_term_data(
                    'tarimpur',
                    'Tarimpur',
                    'Union Auliapur',
                    [
                        'type' => 'mouza',
                        'parent' => 'thana-kotwali',
                        'union' => 'Auliapur',
                        'thana' => 'Kotwali',
                        'upazila' => 'Dinajpur Sadar',
                        'district' => 'Dinajpur',
                        'division' => 'Rangpur',
                        'jl_no' => 103
                    ]
                ),
            ]);

        }

        protected function generate_term_data(
            string $slug,
            string $name,
            string $description = '',
            array $meta = []
        ): array {
            return [
                'slug' => $slug,
                'name' => isset($meta['jl_no']) ? "{$name} (JL-{$meta['jl_no']})" : $name,
                'description' => $description,
                'meta' => $meta
            ];
        }
    }
}